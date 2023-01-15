<style>
	.weui-cell__ft{
		margin-left: 16px;
	}
	.avatar  img{
		width: 54px;
		height: 54px;
		display: block;
		border-radius: 0%;
		padding-top: 1px;
	}
	.cell-border {
		position: relative;
	}
	.cell-border:before{
	  content: " ";
	  position: absolute;
	  left: 0;
	  top: 0;
	  right: 0;
	  height: 1px;
	  border-top: 1px solid rgba(0,0,0,0.1);
	  border-top: 1px solid var(--weui-FG-3);
	  color: rgba(0,0,0,0.1);
	  color: var(--weui-FG-3);
	  -webkit-transform-origin: 0 0;
	  transform-origin: 0 0;
	  -webkit-transform: scaleY(0.5);
	  transform: scaleY(0.5);
	  z-index: 2;
	}
	.weui-cell .c-padding {
		padding: 12px;
	}
	.hidetxt{
		max-width: 100px;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}
</style>
<link rel="stylesheet" href="http://weui.zhimakaifa.com/css/weui-css/weui-basic.css">
<link rel="stylesheet" href="http://weui.zhimakaifa.com/css/weui-css/weui-search-bar.css" />