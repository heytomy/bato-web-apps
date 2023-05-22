# Displaying Module Information

## Step 1: Controller Function

Inside the `ExampleController`, declare the `show` function as follows:

```php
#[Route('/example/{id}', name: 'app_example_contrat')]
public function show(Example $example, Request $request): Response
{
    return $this->render('example/show.html.twig', [
        'current_page' => 'app_exmaple',
        'example' => $example,
    ]);
}
```
The 'current_page' that this controller is returning helps in making the current page display on the navbar. So you'll probably want to go to the navbar and add Example to the navbar.
```twig
{# rest of the code ... #}
<ul class="d-flex flex-column flex-xl-row justify-content-between align-items-center navbar-nav mr-auto w-100">
	{# the other items ... #}
	{% if app.user %}
		
		<li class="p-2 nav-item 
		{% if current_page is defined and current_page == 'app_example' %}active text-primary{% endif %}">
			<a class="nav-link 
			{% if current_page is defined and current_page == 'app_exmaple' %}text-primary{% endif %}" 
			href="{{ path('app_exmaple') }}">
				<i class="fa-solid fa-envelope"></i>
				SAV
			</a>
		</li>
	{# the other items ... #}
	{% endif %}
</ul>
{# rest of the code ... #}
```
You would edit the icon `<i class="fa-solid fa-envelope"></i>` to something that fits the Module and Voilà!

## Step 2: Create/Edit the Template

Edit the `show.html.twig` template as follows:

```twig
{% extends '_inc/listPage.html.twig' %}

{% block title %}Example{% endblock %}

{% block back_button %}
    {# Add back button code if needed #}
{% endblock %}

{% block list %}
    <div class="border rounded container bg-light p-2">
        <div class="row border border-light rounded bg-client m-2 p-2">
            <h1>{{ example.nom }}</h1>
            <div>Code d'exemple: {{ example.id }}</div>
            {# Add more information depending on the Example entity #}
        </div>
    </div>
{% endblock %}
```

Feel free to customize the template based on the specific information you want to display for the `Example` entity.

That's it! With these steps, you'll be able to display the information of the `Example` module using the `show` route and the corresponding template.
