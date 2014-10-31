<li {{ ($lang === $locale) ? 'class="active"' : '' }}><a href="{{{ route($route, $locale) }}}">{{{ $label }}}</a></li>
