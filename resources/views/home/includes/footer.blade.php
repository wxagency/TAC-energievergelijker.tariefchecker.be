<div class="Footer-main">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="footer">
                    <ul>
                     
                        
                         @if(Session::get('locale')=='nl')
                                @foreach ($link_status as $link)
                                @if ( $link->link_status == 1)
                                    <li>
                                        @if($link->slug == 'terms_conditions')
                                        <a href="{{ trans('home.terms_link') }}" target=_blank>@lang('home.terms&conditions')</a>
                                        @endif
                                        @if($link->slug == 'contact')
                                        <a href="{{ trans('home.contact_link') }}" target=_blank>@lang('home.Contact')</a>
                                        @endif
                                        @if($link->slug == 'frequent_questions')
                                        <a href="{{ trans('home.frequent_link') }}" target=_blank>@lang('home.frequent_questions')</a>
                                        @endif
                                        @if($link->slug == 'powered_by')
                                        <a href="{{ trans('home.powered_by_link') }}" target=_blank>@lang('home.powered_by')</a>
                                        @endif
                                    </li>
                                @else

                                    <li>
                                        @if($link->slug == 'terms_conditions')
                                        <a href="{{ trans('home.terms_link') }}">@lang('home.terms&conditions')</a>
                                        @endif
                                        @if($link->slug == 'contact')
                                        <a href="{{ trans('home.contact_link') }}">@lang('home.Contact')</a>
                                        @endif
                                        @if($link->slug == 'frequent_questions')
                                        <a href="{{ trans('home.frequent_link') }}">@lang('home.frequent_questions')</a>
                                        @endif
                                        @if($link->slug == 'powered_by')
                                        <a href="{{ trans('home.powered_by_link') }}">@lang('home.powered_by')</a>
                                        @endif
                                    </li>
                                @endif
                                @endforeach
                        @else
                                @foreach ($link_status as $link)
                                @if ( $link->link_status == 1)
                                    <li>
                                        @if($link->slug == 'terms_conditions')
                                        <a href="{{ trans('home.terms_link') }}" target=_blank>@lang('home.terms&conditions')</a>
                                        @endif
                                        @if($link->slug == 'contact')
                                        <a href="{{ trans('home.contact_link') }}" target=_blank>@lang('home.Contact')</a>
                                        @endif
                                        @if($link->slug == 'frequent_questions')
                                        <a href="{{ trans('home.frequent_link') }}" target=_blank>@lang('home.frequent_questions')</a>
                                        @endif
                                        @if($link->slug == 'powered_by')
                                        <a href="{{ trans('home.powered_by_link') }}" target=_blank>@lang('home.powered_by')</a>
                                        @endif
                                    </li>
                                @else

                                    <li>
                                        @if($link->slug == 'terms_conditions')
                                        <a href="{{ trans('home.terms_link') }}">@lang('home.terms&conditions')</a>
                                        @endif
                                        @if($link->slug == 'contact')
                                        <a href="{{ trans('home.contact_link') }}">@lang('home.Contact')</a>
                                        @endif
                                        @if($link->slug == 'frequent_questions')
                                        <a href="{{ trans('home.frequent_link') }}">@lang('home.frequent_questions')</a>
                                        @endif
                                        @if($link->slug == 'powered_by')
                                        <a href="{{ trans('home.powered_by_link') }}">@lang('home.powered_by')</a>
                                        @endif
                                    </li>
                                @endif
                                @endforeach

                        @endif
                    </ul>
                    <P>@lang('home.Copyright') {{date('Y')}}</P>
                </div>
            </div>
        </div>
    </div>
</div>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
     <script src="https://kit.fontawesome.com/5371eb2245.js"></script>
     
   