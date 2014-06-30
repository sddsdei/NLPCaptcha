<?php

class XyzController extends AppController {

    var $components = array('NLP');

    function admin_nlp_test() {
        if (!empty($this->data)) {
            $nlpValidated = $this->NLP->NLP_Validate($this->data['nlpIdentifier'], $this->data['nlpAnswer']);
            echo '---->>'.$nlpValidated.'<<----';
            die;
        }
    }

}
