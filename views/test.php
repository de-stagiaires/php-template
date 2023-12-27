{{ extends 'app' }}

<h1>{{ $test }}</h1>
{{ block "title" }}Home{{ endblock }}

{{ block "body" }}
<h1>Welcome</h1>
<p>
    lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper
    congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam
</p>
{{ if $testIF == "tes" }}
    <p>
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam debitis dicta eius, facilis fuga impedit laudantium libero molestias nisi odio quaerat qui quisquam sint suscipit temporibus ullam, vitae voluptate voluptates.
    </p>
{{ elseif }}
    <p> de elseif werkt </p>
{{ else }}
    <p>de else werkt</p>
{{ endif }}

{{ endblock }}