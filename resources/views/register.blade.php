<!DOCTYPE html>
<html lang="en">
<head>
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->


  <!-- Vendor CSS Files -->
  <link href="{{asset('assets/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/css/loginstyle.css')}}" rel="stylesheet">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Регистрация</title>
</head>
<body>
 <!--Main container-->
 <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row border rounded-5 p-3 bg-white shadow box-area">
        <!--Left Box-->
        <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" >
           
        <div class="featured-image mb-3">
                <img src="assets/img/features-3.png" class="img-fluid"alt="" style="width:500px; ">
            </div>
        </div>
        <!--Right Box Box-->
        <div class="col-md-6 right-box">
            
            <form class="row align-items-center " method="post" action="/submit-register">
                @csrf
                <div class="mb-5 ms-auto">
                <img src="assets/img/logo.png" class="logo" width="210px">
                </div>
                <div class="header-text mb-4">
                    <h2>Добро пожаловать</h2>
                    <p>Зарегистрируйтесь пожалуйста</p>
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Email адрес" name="email">
                </div>
                <div class="input-group mb-3">
                    <input type="number" class="form-control form-control-lg bg-light fs-6" placeholder="Возраст" name="age">
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Имя пользователя" name="username">
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Пароль" name="password">
                </div>


                <div class="input-group mb-3">
                    <button class="btn btn-lg btn-danger w-100 fs-6" type="submit">Зарегестрироваться</button>
                </div>
                <div class="input-group mb-3">
                    <a class="btn btn-lg btn-light w-100 fs-6" href="/login" style="width:20px;">Войти</a>
                </div>
         
                
</form>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        </div>
    </div>
 </div>

</body>

</html>