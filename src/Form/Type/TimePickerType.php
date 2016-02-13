<?php

namespace ExampleVendor\Form\Type;

use LogicException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimePickerType extends AbstractType {
  private $view_time_format;
  private $view_date_format;
  private $view_seperator;

  // http://trentrichardson.com/examples/timepicker/ --> Formatting
  private $php_to_js = [
    'Y' => 'yy', // https://github.com/trentrichardson/jQuery-Timepicker-Addon/issues/778
    'm' => 'mm',
    'n' => 'm',
    'F' => 'MM',
    'M' => 'M',
    'd' => 'dd',
    'j' => 'd',
    'G' => 'H',
    'H' => 'HH',
    'g' => 'h',
    'h' => 'hh',
    // 'm' -> Minute with no leading 0
    'i' => 'mm',
    // 's' -> Second with no leading 0
    's' => 'ss',
    // 'l' -> Milliseconds always with leading 0
    'u' => 'c',
    // 't' -> a or p for AM/PM
    // 'T' -> A or P for AM/PM
    'a' => 'tt',
    'A' => 'TT',
    // 'z' -> Timezone as defined by timezoneList
    'P' => 'Z'
  ];

  public function getBlockPrefix() {
    return 'timepicker';
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefined([
      'date_format',
      'seperator'
    ]);

    $resolver->setAllowedTypes('date_format', 'string');
    $resolver->setAllowedTypes('seperator', 'string');

    $resolver->setDefaults([
      'compound' => false,
      'type' => 'datetime',
      'time_format' => 'H:i',
      'date_format' => 'd-m-Y',
      'seperator' => ' '
    ]);
  }

  public function buildForm(FormBuilderInterface $builder, array $options) {
    if (! isset($options['date_format']) AND
        ! isset($options['time_format'])) {
      throw new LogicException('at least one of the following two options must be given: time_format, date_format');
    }

    $this->view_time_format = str_replace(array_keys($this->php_to_js), array_values($this->php_to_js), $options['time_format']);
    $this->view_date_format = str_replace(array_keys($this->php_to_js), array_values($this->php_to_js), $options['date_format']);
    $this->view_seperator = $options['seperator'];

    $format = $options['date_format'] . $options['seperator'] . $options['time_format'];
    //$builder->addModelTransformer(new DateTimeToStringTransformer('UTC', 'UTC', $format));
  }

  public function buildView(FormView $view, FormInterface $form, array $options) {
    $view->vars['time_format'] = $this->view_time_format;
    $view->vars['date_format'] = $this->view_date_format;
    $view->vars['seperator'] = $this->view_seperator;
  }
}
