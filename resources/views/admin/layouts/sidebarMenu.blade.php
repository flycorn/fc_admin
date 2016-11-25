<!-- 后台操作菜单 -->
<ul class="sidebar-menu">
    <li class="header">菜单导航</li>
    <!-- 菜单列表 -->
    @if(count($authMenu))
        @foreach($authMenu as $k => $item)
        <li class="treeview @if(in_array($item['id'], $open_menu)) active @endif">
            <a href="@if(count($item['data']))javascript:;@else $item['url']@endif"><i class="fa {{$item['icon']}}"></i> <span>{{$item['display_name']}}</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>

            {{createBackstageMenu($item['data'], $open_menu)}}
        </li>
        @endforeach
    @endif
</ul>