<!-- This file is used to store sidebar items, starting with Starmoozie\Base 0.9.0 -->
@foreach ($menu as $menu_item)
        @if ($menu_item->children->count() && $menu_item->route === '#')
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon {{ $menu_item->icon ? $menu_item->icon : 'fa fa-list' }}"></i> {{ __("starmoozie::title.".transReplace($menu_item->name)) }}</a>
                <ul class="nav-dropdown-items">
                    @foreach ($menu_item->children as $child)
                        <li class="nav-item">
                            @if ($child->children->count() && $child->route === '#')
                                @includeIf('dynamic_view::sub_sidebar')
                            @else
                                <a class='nav-link' href="{{ starmoozie_url($child->route) }}">
                                    <i class="nav-icon @if($child->route === request()->segment(2)) fas @else far @endif fa-circle"></i> {{ __("starmoozie::title.".transReplace($child->name)) }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            <li class="navitem @if($menu_item->route === request()->segment(2)) active @endif ">
                <a class='nav-link' href="{{ starmoozie_url($menu_item->route) }}">
                    <i class="nav-icon {{ $menu_item->icon ? $menu_item->icon : 'fa fa-list' }}"></i> {{ __("starmoozie::title.".transReplace($menu_item->name)) }}
                </a>
            </li>
        @endif
@endforeach