@extends('admin.layouts.admin-layout')

@section('title', 'Set about us')

@section('about_update_active', 'active')

@section('content')
<h3 class="mb-5">
    Set About us
</h3>
<style>
    .toolbar button {
        font-size: 22px;
        font-weight: bold
    }
</style>
<div class="card" id="add_article">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <div class="card-body" >
        <div>
            <div class="w-100 mb-3 pb-3 gap-2" v-if="languages_data.length > 0"  style="display: grid; grid-template-columns: 1fr 1fr;">
                <div class="swiper-slide" v-for="(language, index) in languages_data.slice(0, 7)" :key="index">
                    <div>
                        <label for="term_trans" class="form-label">About in @{{language.name}} *</label>
                        <textarea type="text" class="form-control" id="term_trans" v-model="descriptions[language.symbol]">
                        </textarea>
                    </div>
                </div>
            </div>

            <div class="mb-3 w-100">
                <br>
                <div class="w-25">
                    <!-- Swiper -->
                    <div class="w-100 mb-3 pb-5">
                        <button type="submit" class="btn btn-primary w-50 form-control" style="height: fit-content" @click="this.getContentArticle().then(() => {this.add()})"> set now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
const { createApp, ref } = Vue;

createApp({
  data() {
    return {
      thumbnail: null,
      title: '',
      content: '',
      images: null,
      showImages: false,
      showUploadPopUp: false,
      image: null,
      choosed_img: null,
      current_article_id: null,
      search_tags: null,
      preview_img: null,
      search: null,
      page: 1,
      total: 0,
      last_page: 0,
      forSlider: false,
      slider_imgs: [],
      showSliderPopUp: false,
      code: '',
      showCodePopUp: false,
      album_imgs: [],
      type: 'article',
      languages_data: null,
      descriptions: {},
    }
  },
  methods: {
    addTag() {
      if (this.tagInput.trim() !== '') {
        this.tags.push(this.tagInput.trim());
        this.tagInput = '';
      }
    },
    removeTag(index) {
      this.tags.splice(index, 1);
    },
    previewThumbnail () {
        this.preview_img = this.choosed_img
        this.showImages = false
    },
    async add() {
      $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`{{ route('about.put') }}`, {
                descriptions: this.descriptions,
            },
            );
            if (response.data.status === true) {
            document.getElementById('errors').innerHTML = ''
            let error = document.createElement('div')
            error.classList = 'success'
            error.innerHTML = response.data.message
            document.getElementById('errors').append(error)
            $('#errors').fadeIn('slow')
            $('.loader').fadeOut()
            setTimeout(() => {
                $('#errors').fadeOut('slow')
                window.location.reload()
            }, 2000);
            } else {
            $('.loader').fadeOut()
            document.getElementById('errors').innerHTML = ''
            $.each(response.data.errors, function (key, value) {
                let error = document.createElement('div')
                error.classList = 'error'
                error.innerHTML = value
                document.getElementById('errors').append(error)
            });
            $('#errors').fadeIn('slow')
            setTimeout(() => {
                $('input').css('outline', 'none')
                $('#errors').fadeOut('slow')
            }, 5000);
            }

        } catch (error) {
            document.getElementById('errors').innerHTML = ''
            let err = document.createElement('div')
            err.classList = 'error'
            err.innerHTML = 'server error try again later'
            document.getElementById('errors').append(err)
            $('#errors').fadeIn('slow')
            $('.loader').fadeOut()

            setTimeout(() => {
            $('#errors').fadeOut('slow')
            }, 3500);

            console.error(error);
        }
    },
    async getAbout() {
        try {
            const response = await axios.get(`{{ route('about.get')}}`
            );
            this.descriptions = response.data && response.data.length > 0 ? response.data : {}
        } catch (error) {
            document.getElementById('errors').innerHTML = ''
            let err = document.createElement('div')
            err.classList = 'error'
            err.innerHTML = 'server error try again later'
            document.getElementById('errors').append(err)
            $('#errors').fadeIn('slow')
            $('.loader').fadeOut()
            this.Tags_data = false
            setTimeout(() => {
                $('#errors').fadeOut('slow')
            }, 3500);

            console.error(error);
        }
    },
    handleOpenImages() {
        this.getImages().then(() => {
            this.showImages = true;
            this.current_article_id = null
        })
    },
    async getLanguages() {
        $('.loader').fadeIn().css('display', 'flex')
        try {
            const response = await axios.post(`/admin/words/get-languages`, {
            },
            );
            if (response.data.status === true) {
                $('.loader').fadeOut()
                this.languages_data = response.data.data
            } else {
                $('.loader').fadeOut()
                document.getElementById('errors').innerHTML = ''
                $.each(response.data.errors, function (key, value) {
                    let error = document.createElement('div')
                    error.classList = 'error'
                    error.innerHTML = value
                    document.getElementById('errors').append(error)
                });
                $('#errors').fadeIn('slow')
                setTimeout(() => {
                    $('input').css('outline', 'none')
                    $('#errors').fadeOut('slow')
                }, 5000);
            }

        } catch (error) {
            document.getElementById('errors').innerHTML = ''
            let err = document.createElement('div')
            err.classList = 'error'
            err.innerHTML = 'server error try again later'
            document.getElementById('errors').append(err)
            $('#errors').fadeIn('slow')
            $('.loader').fadeOut()
            this.languages_data = false
            setTimeout(() => {
                $('#errors').fadeOut('slow')
            }, 3500);

            console.error(error);
        }
    },
    execCommand(command) {
        document.execCommand(command, false, null);
    },
    insertHTML(html, element, key) {
        document.getElementById(element).focus();
        document.execCommand('insertHTML', false, html);
        document.execCommand('insertHTML', false, "<br>");
    },
    insertSliderContent(element) {
        if (this.slider_imgs.length > 3) {
            // Get the target element where you want to insert the content
            var targetElement = document.getElementById(element);

            // Get the content from the 'slider' element
            var sliderContent = document.getElementById('slider').innerHTML;
            document.getElementById(element).focus();
            document.execCommand('insertHTML', false, sliderContent);
            this.showSliderPopUp = false;
            this.slider_imgs = []
            this.setValuesToNull()
        } else {
            $("#errors").fadeIn("slow");
            document.getElementById("errors").innerHTML = "";
            let error = document.createElement("div");
            error.classList = "error";
            error.innerHTML =
                "يجب ان يحتوي الاسلايدر علي اربعة صور ع الاقل";
            document.getElementById("errors").append(error);
            setTimeout(() => {
                $("#errors").fadeOut("slow");
            }, 2000);
        }
    },
    insertAlbumContent(element) {
        if (this.album_imgs.length > 2) {
            // Get the target element where you want to insert the content
            var targetElement = document.getElementById(element);

            // Get the content from the 'slider' element
            var sliderContent = document.getElementById('album').innerHTML;
            document.getElementById(element).focus();
            document.execCommand('insertHTML', false, sliderContent);
            this.showAlbumPopUp = false;
            this.album_imgs = []
            this.setValuesToNull()
        } else {
            $("#errors").fadeIn("slow");
            document.getElementById("errors").innerHTML = "";
            let error = document.createElement("div");
            error.classList = "error";
            error.innerHTML =
                "يجب ان يحتوي الالبوم علي تلاثة صور ع الاقل";
            document.getElementById("errors").append(error);
            setTimeout(() => {
                $("#errors").fadeOut("slow");
            }, 2000);
        }
    },
    photoChanges(event) {
        this.thumbnail = event.target.files[0];
        var file = event.target.files[0];
        var fileType = file.type;
        var validImageTypes = ["image/gif", "image/jpeg", "image/jpg", "image/png"];
        if ($.inArray(fileType, validImageTypes) < 0) {
            document.getElementById("errors").innerHTML = "";
            let error = document.createElement("div");
            error.classList = "error";
            error.innerHTML =
                "Invalid file type. Please choose a GIF, JPEG, or PNG image.";
            document.getElementById("errors").append(error);
            $("#errors").fadeIn("slow");
            setTimeout(() => {
                $("#errors").fadeOut("slow");
            }, 2000);

            $(this).val(null);
            $("#preview").attr(
                "src",
                "/Moheb/dashboard/images/add_image.svg"
            );
            $(".photo_group i").removeClass("fa-edit").addClass("fa-plus");
        } else {
            // display image preview
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#preview").attr("src", e.target.result);
                $(".photo_group  i")
                    .removeClass("fa-plus")
                    .addClass("fa-edit");
                $(".photo_group label >i").fadeOut("fast");
            };
            reader.readAsDataURL(file);
        }
    },
    imageChanges(event) {
        this.image = event.target.files[0];
                // check if file is valid image
        var file = event.target.files[0];
        var fileType = file.type;
        var validImageTypes = ["image/gif", "image/jpeg", "image/jpg", "image/png"];
        if ($.inArray(fileType, validImageTypes) < 0) {
            document.getElementById("errors").innerHTML = "";
            let error = document.createElement("div");
            error.classList = "error";
            error.innerHTML =
                "Invalid file type. Please choose a GIF, JPEG, or PNG image.";
            document.getElementById("errors").append(error);
            setTimeout(() => {
                $("#errors").fadeOut("slow");
            }, 2000);

            $(this).val(null);
        } else {
            // display image preview
            var reader = new FileReader();
            reader.onload = function (e) {
            };
            reader.readAsDataURL(file);
        }

    },
    chooseImage(imagePath) {
        this.choosed_img = '/dashboard/images/uploads/' + imagePath;
    },
    setValuesToNull () {
        this.chooseImage = null
        this.current_article_id = null
        this.showImages = null
        this.forAlbum = false
        this.forSlider = false
    },
    insertImgToArticle () {
        if (this.current_article_id) {
            if (this.choosed_img) {
                this.insertHTML('<img src="' + this.choosed_img + '" />', this.current_article_id)
                this.chooseImage = null
                this.current_article_id = null
                this.showImages = null
            }else {
                document.getElementById('errors').innerHTML = ''
                let err = document.createElement('div')
                err.classList = 'error'
                err.innerHTML = 'Please Choose an image or uploade one'
                document.getElementById('errors').append(err)
                $('#errors').fadeIn('slow')
                $('.loader').fadeOut()
                setTimeout(() => {
                    $('#errors').fadeOut('slow')
                }, 3500);

            }
        } else if (this.forSlider) {
            this.slider_imgs.push(this.choosed_img)
            this.chooseImage = null
            this.current_article_id = null
            this.showImages = null
        } else if (this.forAlbum) {
            this.album_imgs.push(this.choosed_img)
            this.chooseImage = null
            this.current_article_id = null
            this.showImages = null
        }
        else {
            this.previewThumbnail()
        }

    },
    async getContentArticle() {
        console.log(this.languages_data);
        if (document.getElementById('article-content') && document.getElementById('article-content').innerHTML != '')
            this.content = document.getElementById('article-content').innerHTML;
    },
  },
  created() {
    this.getAbout()
    this.getLanguages()
    $(".loader").fadeOut()
  },
  mounted() {
    $(document).on('click', '.imgs .img', function () {
        $(this).css('border', '1px solid #13DEB9')
        $(this).siblings().css('border', 'none')
    })
  },
}).mount('#add_article')
</script>
@endsection
