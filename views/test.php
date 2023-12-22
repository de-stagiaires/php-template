{{ extends 'app' }}

<h1>{{ $test }}</h1>
{{ block "title" }}Home{{ endblock }}

{{ block "body" }}
<h1>Welcome</h1>
<p>
    lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper
    congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam
</p>
{{ endblock }}