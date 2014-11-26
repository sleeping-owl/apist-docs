<li {{ ($lang === $locale) ? 'class="active"' : '' }}><a href="{{{ route($route, [$locale, $syntax]) }}}">{{{ $label }}}</a></li>
