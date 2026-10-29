<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * 防护：RefreshDatabase 等 trait 会清空数据库，测试只允许连接 sqlite 内存库，
     * 或主机在本地、且库名包含 "test" 的数据库。检查的是最终生效的配置
     * （含 config:cache、DB_URL、shell 环境变量），不满足时直接中止测试。
     */
    protected function refreshApplication()
    {
        parent::refreshApplication();

        $this->guardAgainstNonTestingDatabase();
    }

    protected function guardAgainstNonTestingDatabase()
    {
        $name = $this->app['config']->get('database.default');
        $config = $this->app['db']->connection($name)->getConfig();

        $driver = isset($config['driver']) ? $config['driver'] : '';
        $database = isset($config['database']) ? (string) $config['database'] : '';

        if ($driver === 'sqlite' && ($database === ':memory:' || strpos($database, 'mode=memory') !== false)) {
            return;
        }

        $hosts = [];
        foreach ([$config, isset($config['read']) ? $config['read'] : [], isset($config['write']) ? $config['write'] : []] as $part) {
            if (isset($part['host'])) {
                $hosts = array_merge($hosts, (array) $part['host']);
            }
        }

        $isLocal = $driver === 'sqlite' || array_diff($hosts, ['127.0.0.1', 'localhost', '::1', 'mysql', 'mariadb', 'pgsql']) === [];
        $isTestDatabase = stripos($driver === 'sqlite' ? basename($database) : $database, 'test') !== false;

        if (! $isLocal || ! $isTestDatabase) {
            throw new \RuntimeException(sprintf(
                '已阻止测试：数据库连接 [%s] 指向 %s/%s，不是测试库。请使用 sqlite :memory: 或本地 *test* 库（并确认没有 bootstrap/cache/config.php 配置缓存）。',
                $name, implode(',', $hosts) ?: $driver, $database
            ));
        }
    }
}
