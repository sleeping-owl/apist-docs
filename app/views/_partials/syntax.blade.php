<li {{ ($syntax === $_syntax) ? 'class="active"' : '' }}><a href="{{{ route($route, [$lang, $_syntax]) }}}">{{{ $label }}}</a></li>
