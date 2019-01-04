
<aside class="menu">
@foreach($menu_items as $menu_key => $menu_item)
    @if(is_string($menu_key))
        @if(strpos($menu_key, 'title') === 0) <p class="menu-label">{{ $menu_item }}</p> @endif
        @if(strpos($menu_key, 'section') === 0)
                <ul class="menu-list">

                @foreach($menu_item as $menu_link => $menu_link_item)
                        <li>
                        @if(strpos($menu_link, 'submenu') === 0)
                            <ul>
                            @foreach($menu_link_item as $sub_menu_link => $sub_menu_item)
                                <li><a href="{{ $sub_menu_link }}">{{ $sub_menu_item }}</a></li>
                            @endforeach
                            </ul>
                        @endif

                    @if(is_string($menu_link_item))
                        <a href="{{ $menu_link }}">{{ $menu_link_item }}</a>
                    @endif
                        </li>
                @endforeach
                </ul>
        @endif
    @endif
@endforeach
</aside>