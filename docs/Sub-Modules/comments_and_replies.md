# Creating a Comment System for a Module in Symfony

In this guide, we will walk through the steps to create a comment system for a specific module (e.g., Example) in a Symfony project. The comment system will include the creation of comments and their replies.

## Step 1: Entity Configuration

1. Create the Comment entity called `CommentairesExample` with the following properties:
   - `contenu`: text, not nullable
   - `date_com`: datetime, not nullable
   - `nom`: string (30 characters), nullable
   - `codeExample`: ManyToOne relation with the `Example` entity, not nullable
   - `owner`: ManyToOne relation with the `DefAppsUtilisateur` entity, not nullable
2. Create the Reply entity called `RepCommentairesExample` with similar properties as the Comment entity:
   - `contenu`: text, not nullable
   - `date_com`: datetime, not nullable
   - `nom`: string (30 characters), nullable
   - `codeExample`: ManyToOne relation with the `Example` entity, not nullable
   - `owner`: ManyToOne relation with the `DefAppsUtilisateur` entity, not nullable
   - `parent`: ManyToOne relation with the `CommentairesExample` entity, not nullable

Note: The `codeExample`, `owner`, and `parent` properties are ManyToOne relations with other entities in the code. Adding `codeExample` inside replies is redundant but simplifies querying for replies associated with a specific Example.

## Step 2: Create Comment and Reply Forms

1. Generate the Comment form using the Symfony console command:

```shell
symfony console make:form CommentairesExampleType
```

2. Open the generated form file located in the `/src/Form` folder and customize it according to your entity. Here's an example:

```php
class CommentairesExampleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Veuillez écrire un commentaire',
                'attr' => [
                    'placeholder'   => 'Veuillez écrire un commentaire',
                    'class'         => 'form-control mr-sm-2',
                    'autocomplete'  =>  'off',
                    'spellcheck'    =>  'false',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommentairesExample::class,
        ]);
    }
}
```

For the Reply form, add a hidden field that stores the `parentId`:

```php
   $builder
       ->add('contenu', TextareaType::class, [
           'label' => 'Veuillez écrire une réponse',
           'attr' => [
               'class'         => 'form-control mr-sm-2',
               'autocomplete'  =>  'off',
               'spellcheck'    =>  'false',
           ],
       ])
       ->add('parentid', HiddenType::class, [
           'mapped' => false
       ])
   ;
```

## Step 3: Configure the ExampleController

In the ExampleController, create a `show` function and define the route for it. Here's an example:

```php
#[Route('/example/{id}', name: 'app_example_contrat')]
public function show(
    Example $example,
    CommentairesExampleRepository $commentairesExampleRepository,
    Request $request,
    EntityManagerInterface $em,
    RepCommentairesExampleRepository $repCommentairesExampleRepository
): Response {
    // Fetch comments and replies related to this Example
    $comments = $commentairesExampleRepository->findBy(['codeExample' => $example->getId()]);
    $replies = $repCommentairesExampleRepository->findByExample($example->getId());
    
    // Define variables for user and name
    $user = $this->getUser() ?? null;
    $nom = $user->getIdUtilisateur()->getNom() ." ". $user->getIdUtilisateur()->getPrenom();

    // Create forms for comments and replies
    $comment = new CommentairesExample();
    $commentForm = $this->createForm(CommentairesExampleType::class, $comment);
    $commentForm->handleRequest($request);

    // Handle comment form submission and save data
    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
        $comment
            ->setDateCom(new DateTime())
            ->setNom($nom)
            ->setCodeExample($example)
            ->setOwner($user->getIDUtilisateur());

        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('app_example_show', ['id' => $example->getId()]);
    }

    // Create form for replies
    $reply = new RepCommentairesExample();
    $replyForm = $this->createForm(RepCommentairesExampleType::class, $reply);
    $replyForm->handleRequest($request);

    // Handle reply form submission and save data
    if ($replyForm->isSubmitted() && $replyForm->isValid()) {
        $reply
            ->setDateCom(new DateTime())
            ->setNom($nom)
            ->setOwner($user->getIDUtilisateur())
            ->setCodeExample($example);

        // Get the parent ID from the hidden field
        $parentid = $replyForm->get('parentid')->getData();

        // Retrieve the corresponding parent comment
        $parent = $commentairesExampleRepository->find($parentid);

        // Set the parent for the reply
        $reply->setParent($parent);

        $em->persist($reply);
        $em->flush();

        return $this->redirectToRoute('app_example_show', ['id' => $example->getId()]);
    }

    // Render the template with the Example, comments, forms, and replies
    return $this->render('chantier/show.html.twig', [
        'example'          =>  $example,
        'comments'         =>  $comments,
        'commentForm'      =>  $commentForm,
        'replyForm'        =>  $replyForm,
        'replies'          =>  $replies,
        'current_page'     =>  'app_example',
    ]);
}
```

Ensure that you have injected the necessary repositories and services in the function parameters.

Next step would be to render the comment system in the template. Refer to the next part of this guide.