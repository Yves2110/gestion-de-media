<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('css/app-assets/bootstrap.min.css')}}">
    <title>Document</title>
</head>
<body>
    @yield('content')

    
    <script>
        //script permettant de supprimer le bouton lors de l'affichage d'une erreur
        // Attendez que le DOM soit chargé
        document.addEventListener('DOMContentLoaded', function () {
            // Sélectionnez le bouton de fermeture
            var closeButton = document.querySelector('.btn-close');
    
            // Ajoutez un écouteur d'événements pour la fermeture de l'alerte
            closeButton.addEventListener('click', function () {
                // Sélectionnez l'élément parent de l'alerte pour le masquer
                var alertContainer = closeButton.closest('.alert');
                
                // Masquer l'alerte
                alertContainer.style.display = 'none';
            });
        });
    </script>
</body>
</html>