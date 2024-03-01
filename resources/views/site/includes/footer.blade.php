<footer>
    <div class="top">
        <a href="">@{{ page_content ? page_content.footer.nav.link_1 : "Privacy Policy" }}</a>
        <a href="">@{{ page_content ? page_content.footer.nav.link_2 : "Cookie Policy" }}</a>
        <a href="">@{{ page_content ? page_content.footer.nav.link_3 : "Contact Us" }}</a>
    </div>
    <div class="bottom">
        @{{ page_content ? page_content.footer.copy : "Powerd By Center De Paris" }}
    </div>
</footer>