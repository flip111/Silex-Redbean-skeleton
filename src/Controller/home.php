<?php
use RedBeanPHP\R;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type as F;
use ExampleVendor\Form\Type\TimePickerType;

$app->match('/', function (Request $request) use ($app) {
  // make a form
  $emptyForm = $app['form.factory']->createBuilder()
    ->add('todo', F\TextType::class)
    ->add('time', TimePickerType::class)
    ->add('save', F\SubmitType::class)
    ->getForm();

  // clone the form so that we can present the user an empty form later on
  $submittedForm = clone $emptyForm;

  // process $_POST data
  $submittedForm->handleRequest($request);

  // check if form is valid
  if ($submittedForm->isValid()) {
    // store data in database
    $data = $submittedForm->getData();
    $todo = R::dispense('todo');
    $todo->text = $data['todo'];
    $todo->time = new \DateTime($data['time']);
    R::store($todo);
  }

  // get a list of all todos
  $todos = R::findAll('todo');

  // render page
  return $app['twig']->render('home.html.twig', [
    'form' => $emptyForm->createView(),
    'todos' => $todos
  ]);
})->bind('home');