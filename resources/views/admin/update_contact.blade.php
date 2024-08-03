@extends('admin.layouts.admin-layout')

@section('title', 'Set Contact ')

@section('contact_update_active', 'active')

@section('content')
<h3 class="mb-5">
Set Contact data
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
            <div class="w-100 mb-4 gap-2" style="display: grid; grid-template-columns: 1fr 1fr;">
                <div class="w-100">
                    <div>
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" v-model="phone">
                    </div>
                </div>
                <div class="w-100">
                    <div>
                        <label for="email" class="form-label">Email Address </label>
                        <input type="text" class="form-control" id="email" v-model="email">
                    </div>
                </div>
            </div>
            <div class="w-100 mb-4 gap-2" style="display: grid; grid-template-columns: 1fr 1fr;">
                <div class="w-100">
                    <div>
                        <label for="facebook" class="form-label">Facebook </label>
                        <input type="text" class="form-control" id="facebook" v-model="facebook">
                    </div>
                </div>
                <div class="w-100">
                    <div>
                        <label for="instagram" class="form-label">Instagram </label>
                        <input type="text" class="form-control" id="instagram" v-model="instagram">
                    </div>
                </div>
            </div>
            <div class="w-100 mb-4 gap-2" style="display: grid; grid-template-columns: 1fr 1fr;">
                <div class="w-100">
                    <div>
                        <label for="youtube" class="form-label">Youtube </label>
                        <input type="text" class="form-control" id="youtube" v-model="youtube">
                    </div>
                </div>
                <div class="w-100">
                    <div>
                        <label for="x" class="form-label">X (Twitter) </label>
                        <input type="text" class="form-control" id="x" v-model="x">
                    </div>
                </div>
            </div>
            <div class="w-100 mb-4 gap-2" style="display: grid; grid-template-columns: 1fr 1fr;">
                <div class="w-100">
                    <div>
                        <label for="copy" class="form-label">copy </label>
                        <textarea type="text" class="form-control" id="copy" v-model="copy">
                        </textarea>
                    </div>
                </div>
                <div class="w-100">
                    <div>
                        <label for="privacy" class="form-label">privacy </label>
                        <textarea type="text" class="form-control" id="privacy" v-model="privacy">
                        </textarea>
                    </div>
                </div>
            </div>

            <div class="mb-3 w-100">
                <br>
                <div class="w-25">
                    <!-- Swiper -->
                    <div class="w-100 mb-3 pb-5">
                        <button type="submit" class="btn btn-primary w-50 form-control" style="height: fit-content" @click="this.getContentArticle().then(() => {this.add()})"> Save</button>
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
        phone: null,
        email: null,
        facebook: null,
        instagram: null,
        tiktok: null,
        youtube: null,
        x: null,
        spotify: null,
        anghami: null,
        privacy: null,
        copy: null,
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
            const response = await axios.post(`/admin/add-contact`, {
                phone: this.phone,
                email: this.email,
                facebook: this.facebook,
                instagram: this.instagram,
                tiktok: this.tiktok,
                youtube: this.youtube,
                x: this.x,
                spotify: this.spotify,
                anghami: this.anghami,
                privacy: this.privacy,
                copy: this.copy,
            },
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
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
    async getContact() {
        try {
            const response = await axios.get(`/admin/get-contact`
            );
            if (response.data.status === true) {
                this.phone = response.data.data.contact ? response.data.data.contact.phone : null
                this.email = response.data.data.contact ? response.data.data.contact.email : null
                this.facebook = response.data.data.contact ? response.data.data.contact.facebook : null
                this.instagram = response.data.data.contact ? response.data.data.contact.instagram : null
                this.tiktok = response.data.data.contact ? response.data.data.contact.tiktok : null
                this.youtube = response.data.data.contact ? response.data.data.contact.youtube : null
                this.x = response.data.data.contact ? response.data.data.contact.x : null
                this.spotify = response.data.data.contact ? response.data.data.contact.spotify : null
                this.anghami = response.data.data.contact ? response.data.data.contact.anghami : null
                this.privacy = response.data.data.contact ? response.data.data.contact.privacy : null
                this.copy = response.data.data.contact ? response.data.data.contact.copy : null
            } else {
            }

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
    this.getContact().then(() => {
        $(".loader").fadeOut()
    })
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
