<header>
    <div class="top">
        <div class="container">
            <div style="display: flex;align-items: center; gap: 8px">
                <a href="/" class="logo">
                    <img src="{{ asset('/site/imgs/logo-new.png') }}?V={{time()}}" alt="">
                </a>
                <div class="icons_header" style="display: flex;align-items: center;gap: 8px">
                    <span style="display: flex;align-items: center;gap: 4px;color: #fff; width: max -content">

                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off" width="35" height="35" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                        <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
                        <path d="M3 3l18 18" />
                    </svg>
                    Supports <br> visually <br>impaired
                    </span>
                    <span style="display: flex;align-items: center;gap: 4px;color: #fff; width: max-content">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-article" width="35" height="35" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                            <path d="M7 8h10" />
                            <path d="M7 12h10" />
                            <path d="M7 16h10" />
                        </svg>
                        Blog
                    </span>

                    <span style="display: flex;align-items: center;gap: 4px;color: #fff; width: max-content">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world-latitude" width="35" height="35" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M4.6 7l14.8 0" />
                        <path d="M3 12l18 0" />
                        <path d="M4.6 17l14.8 0" />
                    </svg>
                    Text Translation
                    </span>
                </div>
            </div>
            <div class="search">
                <!-- Added v-model binding and @input event handler for search functionality -->
                <input type="text" name="search" id="search" placeholder="Search for Term" v-model="search" @input="handleSearch">
                <a :href="`/search/${search}`" >
                    <i class="fa fa-search"></i>
                </a>
                <!-- Added suggestion box for search results -->
                <div class="suggestion" v-if="searchArticles.length && search" style="  z-index: 999999;font-size: 17px;position: absolute;top: 100%;display: flex;flex-direction: column;background: white;width: 100%;border-radius: 10px;margin-top: 10px;">
                    <a :href="`/term/${item.name.replace(/\//g, '').replace(/\s+/g, '-')}/${item.id}`"  v-for="item in searchArticles.slice(0, 5)" :key="item.id" style="font-size: 16px;border-bottom: 1px solid #80808052;padding: 5px 1rem;color: #1a3467;">@{{item.titles[0].title}}<span>@{{ item.category.names[0].name }}</span></a>
                    <a :href="`/search/${search}`" style="font-size: 16px;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;padding: 5px 1rem;color: #1a3467;text-align: center;">Show All</a>
                </div>
            </div>
            <div class="social">
                @php
                    $contact = App\Models\Contact::first();
                @endphp

                @if($contact->facebook)
                <a  href="{{$contact->facebook}}">
                    <i class="fa-brands fa-facebook-f"></i></a>
                @endif
                @if($contact->instagram)
                <a  href="{{$contact->instagram}}">
                    <i class="fa-brands fa-instagram"></i></a>
                @endif
                @if($contact->x)
                    <a  href="{{$contact->x}}">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                @endif
                @if($contact->youtube)
                    <a  href="{{$contact->youtube}}">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                @endif
            </div>
            {{-- Removed the about and contact links --}}
            {{-- <div class="links">
                <a href="/about-us">@{{ page_content ? page_content.header.about : "about" }}</a>
                <a href="/contact-us">@{{ page_content ? page_content.header.contact : "contact" }}</a>
            </div> --}}
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px">
                @if(Auth::user())
                <div class="profile">
                    <div class="text" @click="showProfileMore == true ? showProfileMore = false : showProfileMore = true">
                        <p>Welcome</p>
                        <h4>{{Auth::user()?->email}} <i class="fa fa-angle-down"></i></h4>
                    </div>
                    <div class="img" @click="showProfileMore == true ? showProfileMore = false : showProfileMore = true">
                        <img src="{{ asset('/site/imgs/profile.jpg') }}" alt="profile images">
                    </div>
                    <!-- Added profile dropdown menu -->
                    <div class="profile-more" v-if="showProfileMore">
                        <a href="/my-wishlist">@{{ page_content ? page_content.sections.my_wishlist : "My Wishlist" }}</a>
                        <a href="{{ route('site.logout') }}">Logot</a>
                    </div>
                </div>
                @endif
                @if(!Auth::user())
                <div class="profile">
                    <a href="{{ route('site.login') }}">@{{ page_content.login }}</a>
                    <a href="{{ route('site.register') }}">@{{ page_content.register }}</a>
                </div>
                @endif
                <div class="lang">
                    <select v-model="current_lang" v-if="languages_data && languages_data.length" @change="setLang">
                        <option :value="language.symbol" v-for="(language, index) in languages_data" :key="index">@{{ language.name }}</option>
                    </select>
                    <h3><i class="fa fa-globe"></i> <span class="lang_symbol">@{{current_lang}}</span> <i class="fa-solid fa-caret-down"></i></h3>
                </div>
            </div>
        </div>
        <div class="mobile_icons_header" style="display: flex;align-items: center;gap: 8px;justify-content: center">
            <span style="display: flex;align-items: center;gap: 4px;color: #fff; width: max -content">

                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off" width="30" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                    <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
                    <path d="M3 3l18 18" />
                </svg>
                Supports visually <br> impaired
                </span>
                <span style="display: flex;align-items: center;gap: 4px;color: #fff; width: max-content">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-article" width="30" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                        <path d="M7 8h10" />
                        <path d="M7 12h10" />
                        <path d="M7 16h10" />
                    </svg>
                    Blog
                </span>

                <span style="display: flex;align-items: center;gap: 4px;color: #fff; width: max-content">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world-latitude" width="30" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                    <path d="M4.6 7l14.8 0" />
                    <path d="M3 12l18 0" />
                    <path d="M4.6 17l14.8 0" />
                </svg>
                Text Translation
                </span>
        </div>
    </div>
    <div class="bottom">
        <div class="container" style="overflow: visible !important">
            <div class="categories" v-if="all_categories && all_categories.length" style=" max-width: 100%;overflow: auto;">
                <!-- Added dynamic categories rendering -->
                <a :href="`/all-sports`" >@{{ page_content.sections.other_sports }}</a>
                <div  v-for="cat in all_categories" :class="cat.main_name == 'Football' ? 'has-drop' : ''" :key="cat.id" style="position: relative">
                    <a :href="`/category/${cat.id}`"  style="display: block !important;">
                        @{{cat.name}}
                        <div style=" left: 0px; top: 165px;left: 50%;transform: translateX(-50%);position: fixed" dir="ltr">
                            <div class="container" style="display: block">
                                <div class="drop" v-if="cat.main_name == 'Football'" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;display: none;width: max-content;z-index: 9999;background: rgb(0, 65, 106); z-index: 9999; flex-direction: revert; justify-content: start; gap: 20px; padding: 12px; border-radius: 8px; flex-wrap: wrap;flex-direction: column;background: white;">
                                    <a :href="`/category/${sub_cat.id}`" style="display: block;color: #000; padding: 4px; min-width: 180px" v-for="sub_cat in cat?.sub_categories">
                                        @{{sub_cat.names[0] ? sub_cat.names[0]["name"] : sub_cat.main_name}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="more">
                <i class="fa fa-bars"></i>
            <div class="mobile-menu">
                <div class="close">
                    <i class="fa fa-close"></i>
                </div>
                <form action="" id="searchForm">
                    <div class="search">
                        <!-- Added v-model binding and @input event handler for mobile search functionality -->
                        <input type="text" name="search" id="search" placeholder="Search for Term" v-model="search" @input="handleSearch" style="  padding-left: 3rem;">
                        <a :href="`/search/${search}`" >
                            <i class="fa fa-search"></i>
                        </a>
                        <!-- Added suggestion box for search results -->
                        <div class="suggestion" v-if="searchArticles.length && search" style="  z-index: 999999;z-index: 999;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;position: absolute;top: 100%;display: flex;flex-direction: column;background: white;width: 100%;border-radius: 10px;margin-top: 10px;">
                            <a :href="`/term/${item.name.replace(/\//g, '').replace(/\s+/g, '-')}/${item.id}`"  v-for="item in searchArticles.slice(0, 5)" :key="item.id" style="font-size: 16px;border-bottom: 1px solid #80808052;padding: 5px 1rem;color: #1a3467;">@{{item.titles[0].title}}<span>@{{ item.category.names[0].name }}</span></a>
                            <a :href="`/search/${search}`" style="font-size: 16px;padding: 5px 1rem;color: #1a3467;text-align: center;">Show All</a>
                        </div>
                    </div>
                </form>
                <div class="social">
                    <!-- Added social media links -->
                    <a href=""><i class="fa-brands fa-facebook-f"></i></a>
                    <a href=""><i class="fa-brands fa-instagram"></i></a>
                    <a href=""><i class="fa-brands fa-twitter"></i></a>
                    <a href=""><i class="fa-brands fa-youtube"></i></a>
                </div>
                <div class="links">
                    <a href="/about-us">@{{ page_content ? page_content.header.about : "About" }}</a>
                    <a href="/contact-us">@{{ page_content ? page_content.header.contact : "Contact" }}</a>
                    <a href="/blog">@{{ page_content ? page_content.header.blog : "Blog" }}</a>
                </div>
                <div class="categories" v-if="all_categories && all_categories.length" style="display: flex;flex-direction: column">
                    <a :href="`/all-sports`" style="display: block !important">@{{ page_content.sections.other_sports }}</a>
                    <a :href="`/category/${cat.id}`" style="display: block !important" v-for="cat in all_categories" :key="cat.id">@{{cat.name}}</a>
                </div>
                <div style="display: flex; justify-content: center; align-items: center; gap: 10px">
                    <div class="lang">
                        <select v-model="current_lang" v-if="languages_data && languages_data.length" @change="setLang">
                            <option :value="language.symbol" v-for="(language, index) in languages_data" :key="index">@{{ language.name }}</option>
                        </select>
                        <h3><i class="fa fa-globe"></i> <span class="lang_symbol">@{{current_lang}}</span> <i class="fa-solid fa-caret-down"></i></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<span class="space-header" style="width: 100%;display:block"></span>
