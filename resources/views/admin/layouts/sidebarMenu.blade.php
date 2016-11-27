<!-- 后台操作菜单 -->
<ul class="sidebar-menu">
    <li class="header">导航菜单</li>
    <!-- 菜单列表 -->
    @if(count($authMenus))
        {{createAdminMenu($authMenus, $openMenus)}}
    @endif
</ul>