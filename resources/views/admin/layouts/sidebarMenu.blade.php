<!-- 后台操作菜单 -->
<ul class="sidebar-menu">
    <li class="header">菜单导航</li>
    <!-- 菜单列表 -->
    @if(count($authMenus))
        {{createAdminMenu($authMenus, $openMenus)}}
    @endif
</ul>