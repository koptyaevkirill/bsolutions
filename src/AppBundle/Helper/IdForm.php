<?php
namespace AppBundle\Helper;
use Symfony\Component\Form\Form;

class IdForm {
    public function getFormErrors(Form $form) {
        $fields = [];
        foreach ($form as $key=>$value) {
            $error = $this->getFieldError($key, $form);
            if (count($error)>0) {
                $fields[$key] = $error;
            }
        }
        return $fields;
    }
    public function getFieldError($field, $form) {
        $errors = [];
        $fe = $form[$field]->getErrors();
        foreach ($fe as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }
    public function translateErrors($errors, $translator) {
        $result = [];
        foreach ($errors as $key => $value) {
            foreach ($value as $error) {
                $result[$key][] = $translator->trans($error, [], 'validators');
            }
        }
        return $result;
    }
    
}
