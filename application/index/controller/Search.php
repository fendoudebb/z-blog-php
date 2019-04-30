<?php

namespace app\index\controller;



class Search extends Base {

    public function search($q) {
        $took = 123;
        $hits = 456;

        $this->request->__set("took", $took);
        $this->request->__set("hits", $hits);

    }

}