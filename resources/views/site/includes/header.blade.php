<header>
    <div class="top">
        <div class="container">
            <a href="/" class="logo">
                <img src="{{ asset('/site/imgs/logo.png') }}?v={{time()}}" alt="">
            </a>
            <div class="search">
                <input type="text" name="search" id="search" placeholder="Search for Term" v-model="search" @input="handleSearch">
                <i class="fa fa-search" @click="handleSearch"></i>
                <div class="suggestion" v-if="searchArticles.length && search" style="font-size: 17px;position: absolute;top: 100%;display: flex;flex-direction: column;background: white;width: 100%;border-radius: 10px;margin-top: 10px;">
                    <a :href="`/term/${item.name}/${item.id}`"  v-for="item in searchArticles.slice(0, 5)" :key="item.id" style="font-size: 16px;border-bottom: 1px solid #80808052;padding: 5px 1rem;color: #1a3467;">@{{item.titles[0].title}}</a>
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
            <div class="links">
                <a href="/about-us">@{{ page_content ? page_content.header.about : "about" }}</a>
                <a href="/contact-us">@{{ page_content ? page_content.header.contact : "contact" }}</a>
            </div>
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px">
                @if(Auth::user())
                <div class="profile" >
                    <div class="text" @click="showProfileMore == true ? showProfileMore = false : showProfileMore = true">
                        <p>Welcome</p>
                        <h4>@{{user.user.email}} <i class="fa fa-angle-down"></i></h4>
                    </div>
                    <div class="img" @click="showProfileMore == true ? showProfileMore = false : showProfileMore = true">
                        <img src="{{ asset('/site/imgs/profile.jpg') }}" alt="profile images">
                    </div>
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
    </div>
    <div class="bottom">
        <div class="container">
            <div class="categories" v-if="all_categories && all_categories.length">
                <a :href="`/category/${cat.id}`" v-for="cat in all_categories" :key="cat.id">@{{cat.name}}</a>
            </div>
            <div class="more">
                <i class="fa fa-bars"></i>
            <div class="mobile-menu">
                <div class="close">
                    <i class="fa fa-close"></i>
                </div>
                <div class="search">
                    <input type="text" name="search" id="search" placeholder="Search for Term" v-model="search" @input="handleSearch">
                    <i class="fa fa-search" @click="handleSearch"></i>
                    <div class="suggestion" v-if="searchArticles.length && search" style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;position: absolute;top: 100%;display: flex;flex-direction: column;background: white;width: 100%;border-radius: 10px;margin-top: 10px;">
                        <a :href="`/term/${item.name}/${item.id}`"  v-for="item in searchArticles.slice(0, 5)" :key="item.id" style="font-size: 16px;border-bottom: 1px solid #80808052;padding: 5px 1rem;color: #1a3467;">@{{item.titles[0].title}}</a>
                        <a :href="`/search/${search}`" style="font-size: 16px;padding: 5px 1rem;color: #1a3467;text-align: center;">Show All</a>
                    </div>
                </div>
                <div class="social">
                    <a href=""><i class="fa-brands fa-facebook-f"></i></a>
                    <a href=""><i class="fa-brands fa-instagram"></i></a>
                    <a href=""><i class="fa-brands fa-twitter"></i></a>
                    <a href=""><i class="fa-brands fa-youtube"></i></a>
                </div>
                <div class="links">
                    <a href="">@{{ page_content ? page_content.header.about : "about" }}</a>
                    <a href="">@{{ page_content ? page_content.header.contact : "contact" }}</a>
                </div>
                <div class="categories" v-if="all_categories && all_categories.length" style="display: flex;flex-direction: column">
                    <a :href="`/category/${cat.id}`" v-for="cat in all_categories" :key="cat.id">@{{cat.name}}</a>
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
    </div>
</header>
<header>
    <div class="top">
        <div class="container">
            <div class="logo">
                <img src="{{ asset('/site/imgs/logo.png') }}?V={{time()}}" alt="">
            </div>
            <div class="search">
                <input type="text" name="search" id="search" placeholder="Search for Term">
                <i class="fa fa-search"></i>
            </div>
            <div class="social">
                <a href=""><i class="fa-brands fa-facebook-f"></i></a>
                <a href=""><i class="fa-brands fa-instagram"></i></a>
                <a href=""><i class="fa-brands fa-twitter"></i></a>
                <a href=""><i class="fa-brands fa-youtube"></i></a>
            </div>
            <div class="links">
                <a href="">About</a>
                <a href="">Contact</a>
            </div>
            <div style="display: flex; justify-content: center; align-items: center; gap: 10px">
                <div class="profile" v-if="user">
                    <div class="text">
                        <p>Welcome</p>
                        <h4>@{{user.user.email}} <i class="fa fa-angle-down"></i></h4>
                    </div>
                    <div class="img">
                        <img src="{{ asset('/site/imgs/profile.jpg') }}" alt="profile images">
                    </div>
                </div>
                <div class="lang">
                    <select v-model="current_lang" v-if="languages_data && languages_data.length" @change="setLang">
                        <option :value="language.symbol" v-for="(language, index) in languages_data" :key="index">@{{ language.name }}</option>
                    </select>
                    <h3><i class="fa fa-globe"></i> <span class="lang_symbol">@{{current_lang}}</span> <i class="fa-solid fa-caret-down"></i></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="container">
            <div class="categories">
                <a href="">Football</a>
                <a href="">Football</a>
                <a href="">Football</a>
                <a href="">Football</a>
                <a href="">Football</a>
            </div>
            <div class="more">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </div>
</header>
