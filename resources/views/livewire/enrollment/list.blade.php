@section('title', $event->name.'|'.$event->organization->system_name)

    <style>
    	.weui-cell__ft{
    		margin-left: 16px;
    	}
    	.avatar  img{
    		width: 54px;
    		height: 54px;
    		display: block;
    		border-radius: 50%;
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
    		max-width: 70px;
    		overflow: hidden;
    		white-space: nowrap;
    		text-overflow: ellipsis;
    	}
    </style>
    <link rel="stylesheet" href="http://weui.zhimakaifa.com/css/weui-css/weui-basic.css">
        <link rel="stylesheet" href="http://weui.zhimakaifa.com/css/weui-css/weui-search-bar.css" />

 <div class="weui-tab">
    <div class="weui-navbar">
        <div role="tab" aria-selected="true" aria-controls="panel1" id="tab1" class="weui-navbar__item weui-bar__item_on">
            已报名
        </div>
        <div role="tab" aria-controls="panel2" id="tab2" class="weui-navbar__item">
            辅助报名
        </div>
    </div>
    <div role="tabpanel" id="panel1" aria-labelledby="tab1" class="weui-tab__panel">
		<div class="container">
			<div class="weui-search-bar" id="searchBar">
		        <form id="searchForm" role="combobox" aria-haspopup="true" aria-expanded="false" aria-owns="searchResult" class="weui-search-bar__form">
		            <div class="weui-search-bar__box">
		                <i class="weui-icon-search"></i>
		                <input type="search" aria-controls="searchResult" class="weui-search-bar__input" id="searchInput" placeholder="搜索" required/>
		                <a href="javascript:" role="button" title="清除" class="weui-icon-clear" id="searchClear"></a>
		            </div>
		            <label for="searchInput" class="weui-search-bar__label" id="searchText">
		                <i class="weui-icon-search"></i>
		                <span aria-hidden="true">搜索</span>
		            </label>
		        </form>
		        <a href="javascript:" role="button" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
		    </div>

		    <div class="weui-panel__bd">
		        <div class="weui-cells__title">B</div>
		        <div class="weui-cells">
		        	@foreach($contacts as $key => $contact)
			 		<div class="weui-flex cell-border">
			            <div class="weui-flex__item">
							<div class="weui-cell  weui-cell_swiped">
				                <div role="option" class="weui-cell__bd" style="transform: translateX(-0px);">
				                    <div class="weui-cell c-padding">
				                        <div class="weui-cell__bd">
				                            <p class="hidetxt">{{$contact['name']}}</p>
				                        </div>
				                        <div class="weui-cell__ft hidetxt">{{$contact['checked_in_at']?'已签入':'未签入'}}</div>
				                        <div class="weui-cell__ft">
					                    	 <label for="switchCP{{$key}}" class="weui-switch-cp">
						                        <input id="switchCP{{$key}}" class="weui-switch-cp__input" type="checkbox" 
						                        {{$contact['checked_in_at']?'checked':''}}>
						                        <div class="weui-switch-cp__box"></div>
						                    </label>
						                </div>
				                    </div>
				                </div>
				                <div class="weui-cell__ft">
				                    <a role="button" class="weui-swiped-btn weui-swiped-btn_warn" href="javascript:">Action</a>
				                </div>
				            </div>
			            </div>
			            <div class="avatar">
			    			<img src="{{$contact['avatar']}}" alt="">
			    		</div>
			        </div>
			        @endforeach

		        </div> 
		    </div>
		</div>
    </div>
    <div style="display: none;" role="tabpanel" id="panel2" aria-labelledby="tab2" class="weui-tab__panel">
		<div class="container">
			<div class="weui-search-bar" id="searchBar">
		        <form id="searchForm" role="combobox" aria-haspopup="true" aria-expanded="false" aria-owns="searchResult" class="weui-search-bar__form">
		            <div class="weui-search-bar__box">
		                <i class="weui-icon-search"></i>
		                <input type="search" aria-controls="searchResult" class="weui-search-bar__input" id="searchInput" placeholder="搜索" required/>
		                <a href="javascript:" role="button" title="清除" class="weui-icon-clear" id="searchClear"></a>
		            </div>
		            <label for="searchInput" class="weui-search-bar__label" id="searchText">
		                <i class="weui-icon-search"></i>
		                <span aria-hidden="true">搜索</span>
		            </label>
		        </form>
		        <a href="javascript:" role="button" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
		    </div>

		    <div class="weui-panel__bd">
		        <div class="weui-cells__title">B</div>
		        <div class="weui-cells">
		        	@foreach($potentialContacts as $contact)
			 		<div class="weui-flex cell-border">
			            <div class="weui-flex__item">
							<div class="weui-cell  weui-cell_swiped">
				                <div role="option" class="weui-cell__bd" style="transform: translateX(-0px);">
				                    <div class="weui-cell c-padding">
				                        <div class="weui-cell__bd">
				                            <p class="hidetxt">{{$contact['name']}}</p>
				                        </div>
				                        <div class="weui-cell__ft hidetxt">{{$contact['name']}}</div>
				                        <div class="weui-cell__ft">
					                    	 <label for="switchCP2" class="weui-switch-cp">
						                        <input id="switchCP2" class="weui-switch-cp__input" type="checkbox" checked="checked">
						                        <div class="weui-switch-cp__box"></div>
						                    </label>
						                </div>
				                    </div>
				                </div>
				                <div class="weui-cell__ft">
				                    <a role="button" class="weui-swiped-btn weui-swiped-btn_warn" href="javascript:">Action</a>
				                </div>
				            </div>
			            </div>
			            <div class="avatar">
			    			<img src="{{$contact['avatar']}}" alt="">
			    		</div>
			        </div>
			        @endforeach

		        </div> 
		    </div>
		</div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('.weui-navbar__item').on('click', function () {
            $(this).attr('aria-selected','true').addClass('weui-bar__item_on');
            $(this).siblings('.weui-bar__item_on').removeClass('weui-bar__item_on').attr('aria-selected','false');
            var panelId = '#' + $(this).attr('aria-controls');
            $(panelId).css('display','block');
            $(panelId).siblings('.weui-tab__panel').css('display','none');
        });
    });


    $(function(){
        var $searchBar = $('#searchBar'),
            $searchResult = $('#searchResult'),
            $searchText = $('#searchText'),
            $searchInput = $('#searchInput'),
            $searchClear = $('#searchClear'),
            $searchForm = $('#searchForm'),
            $searchCancel = $('#searchCancel');

        function hideSearchResult(){
            $searchResult.hide();
            $searchForm.attr('aria-expanded','false');
            $searchInput.val('');
        }
        function cancelSearch(){
            hideSearchResult();
            $searchBar.removeClass('weui-search-bar_focusing');
            $searchText.show();
        }

        $searchText.on('click', function(){
            $searchBar.addClass('weui-search-bar_focusing');
            $searchInput.focus();
        });
        $searchInput
            .on('blur', function () {
                if(!this.value.length) cancelSearch();
            })
            .on('input', function(){
                if(this.value.length) {
                    $searchResult.show();
                    $searchForm.attr('aria-expanded','true');
                } else {
                    $searchResult.hide();
                    $searchForm.attr('aria-expanded','false');
                }
            })
        ;
        $searchClear.on('click', function(){
            hideSearchResult();
            $searchInput.focus();
        });
        $searchCancel.on('click', function(){
            cancelSearch();
            $searchInput.blur();
        });
    });
</script>

