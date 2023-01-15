@section('title', $event->name.'|'.$event->organization->system_name)
<div>
@include('livewire.event.style')
<div class="weui-tab">
    <div class="weui-navbar">
        <div role="tab" aria-selected="true" aria-controls="panel1" id="tab1" class="weui-navbar__item weui-bar__item_on">
            签入/报名<br/>{{$eventEnrolls->whereNotNull('checked_in_at')->count()}}/{{$eventEnrolls->count()}}
        </div>
        <a href="{{route('helper.by.contacts',$event);}}" role="tab" aria-controls="panel2" id="tab2" class="weui-navbar__item">
            辅助报名
        </a>
        <div role="tab" aria-controls="panel3" id="tab3" class="weui-navbar__item">
            潜在报名
        </div>
    </div>
    <div role="tabpanel" id="panel1" aria-labelledby="tab1" class="weui-tab__panel">
		<div class="container">
			<div class="weui-search-bar" id="searchBar">
		        <form id="searchForm" role="combobox" aria-haspopup="true" aria-expanded="false" aria-owns="searchResult" class="weui-search-bar__form">
		            <div class="weui-search-bar__box">
		                <i class="weui-icon-search"></i>
		                <input 
		                wire:model="search"
		                type="search" aria-controls="searchResult" class="weui-search-bar__input" id="searchInput" placeholder="搜索" required/>
		                <a href="javascript:" role="button" title="清除" class="weui-icon-clear" id="searchClear"></a>
		            </div>
		            <label for="searchInput" class="weui-search-bar__label" id="searchText">
		                <i class="weui-icon-search"></i>
		                <span aria-hidden="true">搜索1</span>
		            </label>
		        </form>
		        <a href="javascript:" role="button" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
		    </div>

		    <div class="weui-panel__bd">
		        <div class="weui-cells__title">bbb</div>
		        <div class="weui-cells">
		        	@foreach($eventEnrolls as $i => $eventEnroll)
		        	@php
		        		$social = $eventEnroll->user->social;
				        $name = $social?($social->nickname?:$social->name):$eventEnroll->user->name;
				        $avatar = $social?$social->avatar:$eventEnroll->user->profile_photo_url;
		        	@endphp
		        		<div class="weui-flex cell-border">
						    <div class="weui-flex__item">
						        <div class="weui-cell  weui-cell_swiped">
						            <div role="option" class="weui-cell__bd" style="transform: translateX(-0px);">
						                <div class="weui-cell c-padding">
						                    <div class="weui-cell__bd">
						                        <p class="hidetxt">{{$name}}</p>
						                    </div>
						                    <div class="weui-cell__ft hidetxt">{{$eventEnroll->is_checked_in}}</div>
						                    <div class="weui-cell__ft">
						                         <label for="switchCP{{$eventEnroll->id}}" class="weui-switch-cp">
						                            <input 
						                            wire:model="eventEnrolls.{{$i}}.is_checked_in"
						                            id="switchCP{{$eventEnroll->id}}" class="weui-switch-cp__input" type="checkbox" 
						                            >
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
						        <img src="{{$avatar}}" alt="">
						    </div>
						</div>
			        @endforeach


		        </div> 
		    </div>
		</div>
    </div>


    <div style="display: none;" role="tabpanel" id="panel3" aria-labelledby="tab3" class="weui-tab__panel">
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
		                <span aria-hidden="true">搜索3</span>
		            </label>
		        </form>
		        <a href="javascript:" role="button" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
		    </div>

		    <div class="weui-panel__bd">
		        <div class="weui-cells__title">B</div>
		        <div class="weui-cells">
		        	TODO 所有活动的参与者 没有报名的
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
</div>