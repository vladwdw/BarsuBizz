<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/formstyle.css') }}" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Составление шаблона</title>
    <style>

    body {
  font-family: 'Inter', 'sans-serif';
/* Замените 'путь_к_вашему_изображению.jpg' на путь к вашему фоновому изображению */
  background-size: cover; /* Растягивать фон до заполнения всего экрана */
  background-repeat: no-repeat; /* Не повторять фон */
  background-attachment: fixed; /* Фиксировать фон, чтобы он не двигался при прокрутке */
};
    </style>
</head>
<body>
    <!--Main container-->
    <div class="container d-flex justify-content-center align-items-center min-vh-100 p-4">
        
        <form method="post" class="col-md-6 right-box p-3 rounded-4 shadow box-area" action="{{ route('form33_update', ['name' => $hundredideas->name,'id' => $hundredideas->id]) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-5 ms-auto">
                <img src="{{asset('assets/img/logo.png')}}" class="logo" width="210px">
        </div>
        <div class="row align-items-center ">
                <div class="header-text mb-4">
                <h3 style="text-align: center;"><strong>ЗАЯВКА </strong></h3>
                    <h4 style="text-align: center;"> на участие в республиканском проекте «100 идей для Беларуси»
                            </h4>
                </div>
                <p>Наименование проекта</p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Научное направление " name="name_project" value="{{$hundredideas->name_project}}">
                </div>
                <p>Авторы проекта (место работы (учебы), должность (курс, класс, группа и др.), телефоны, электронная почта, адрес почты для обмена данными, прочие сведения)</p>
                <div class="form-group mb-3">
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Введите данные:" name="name_autors">{{$hundredideas->name_autors}}</textarea>
                </div>
                
                <p>Актуальность, новизна и оригинальность проекта</p>
                <div class="form-group mb-3">
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Введите данные:" name="relevance">{{$hundredideas->relevance}}</textarea>
                </div>
                <p>Цели и задачи, которые будут решены при реализации проекта </p>
                <div class="form-group mb-3">
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Введите данные:" name="goals_objectives">{{$hundredideas->goals_objectives}}</textarea>
                </div>
                <p>Технические (экономические, социальные) преимущества проекта  </p>
                <div class="form-group mb-3">
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Введите данные:" name="advantages_project">{{$hundredideas->advantages_project}}</textarea>
                </div>
                <p>Сведения об охране интеллектуальной собственности (наличие патента и др.)  </p>
                <div class="form-group mb-3">
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Введите данные:" name="property_protection">{{$hundredideas->property_protection}}</textarea>
                </div>
                <p>Предложения по дальнейшей реализации проекта (с обоснованием)  </p>
                <div class="form-group mb-3">
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Введите данные:" name="offers">{{$hundredideas->offers}}</textarea>
                </div>
                <div class="input-group mb-3">
                    <button class="btn btn-lg btn-danger w-100 fs-6" type="submit">Сохранить</button>
                </div>
                <div class="input-group mb-3">
                <a href="{{ route('back')}}" class="btn btn-lg btn-light w-100 fs-6" style="width:20px;">Назад</a>                </div>
            </div>
        </form>
    </div>
</body>
</html>