<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;
use think\Log;

class English extends BaseRoleNormal {

    public function englishList() {
        $page = intval(input('post.page'));
        $size = intval(input('post.size'));
        if ($page < 1) {
            $page = 1;
        }
        if ($size < 1 || $size > 20) {
            $size = 20;
        }
        $offset = ($page - 1) * $size;

        $cmd = [
            'find' => 'english',
            'projection' => [
                '_id' => 1,
                'word' => 1,
                'english_phonetic' => 1,
                'american_phonetic' => 1,
                'translation' => 1,//数组：property, explanation
                'example_sentence' => 1,
                'sentence_translation' => 1,
                'source' => 1,
            ],
            'sort' => [
                '_id' => -1,
            ],
            'skip' => $offset,
            'limit' => $size,
        ];
        $word = trim(strval(input('post.word')));
        if ($word) {
            /*$cmd['filter'] = [
                'word' => [
                    '$regex' => $word
                ]
            ];*/
            //https://docs.mongodb.com/manual/core/text-search-operators/
            $cmd['filter'] = [
                '$text' => [
                    '$search' => $word
                ]
            ];
            $cmd['projection']['score'] = [
                '$meta' => 'textScore',
            ];
            $cmd['sort'] = [
                'score' => [
                    '$meta' => 'textScore',
                ]
            ];
        }

        $english = Mongo::cmd($cmd);
        Log::log($cmd);
        foreach ($english as $e) {
            $e->_id = $e->_id->__toString();
        }
        $response = [
        ];
        //https://docs.mongodb.com/manual/reference/command/count/
        $cmd = [
            'count' => 'english'
        ];
        if ($word) {
            $cmd['query'] = [
                '$text' => [
                    '$search' => $word
                ]
            ];
        }
        $countResult = Mongo::cmd($cmd);
        Log::log($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['english'] = $english;
        return $this->res($response);
    }

    public function addEnglish() {
        $word = trim(strval(input('post.word')));
        $englishPhonetic = trim(strval(input('post.english_phonetic')));
        $americanPhonetic = trim(strval(input('post.american_phonetic')));
        $translation = input('post.translation/a');
        $exampleSentence = trim(strval(input('post.example_sentence')));
        $sentenceTranslation = trim(strval(input('post.sentence_translation')));
        $source = trim(strval(input('post.source')));
        if (!isset($word)) {
            $this->log(ResCode::MISSING_PARAMS_WORD);
            return $this->fail(ResCode::MISSING_PARAMS_WORD);
        }
        if (!isset($translation)) {
            $this->log(ResCode::MISSING_PARAMS_TRANSLATION);
            return $this->fail(ResCode::MISSING_PARAMS_TRANSLATION);
        }
        foreach ($translation as $t) {
            if (!is_array($t)) {
                $this->log(ResCode::ILLEGAL_ARGUMENT_TRANSLATION_ELEMENT);
                return $this->fail(ResCode::ILLEGAL_ARGUMENT_TRANSLATION_ELEMENT);
            }
            if (!isset($t['property']) || $t['property'] == '') {
                $this->log(ResCode::MISSING_PARAMS_PROPERTY);
                return $this->fail(ResCode::MISSING_PARAMS_PROPERTY);
            }
            if (!isset($t['property']) || $t['explanation'] == '') {
                $this->log(ResCode::MISSING_PARAMS_EXPLANATION);
                return $this->fail(ResCode::MISSING_PARAMS_EXPLANATION);
            }
        }

        if (!ctype_alnum($word)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_WORD);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_WORD);
        }
        $cmd = [
            'find' => 'english',
            'filter' => [
                'word' => $word,
            ],
            'projection' => [
                '_id' => 1
            ],
            'limit' => 1
        ];
        $wordInfo = Mongo::cmd($cmd);
        if ($wordInfo != null) {
            $this->log(ResCode::WORD_ALREADY_EXIST);
            return $this->fail(ResCode::WORD_ALREADY_EXIST);
        }
        $insertEnglishCmd = [
            'insert' => 'english',
            'documents' => [
                [
                    'word' => $word,
                    'english_phonetic' => $englishPhonetic,
                    'american_phonetic' => $americanPhonetic,
                    'translation' => $translation,//数组：property, explanation
                    'example_sentence' => $exampleSentence,
                    'sentence_translation' => $sentenceTranslation,
                    'source' => $source
                ]
            ]
        ];
        $insertEnglishResult = Mongo::cmd($insertEnglishCmd);
        if (empty($insertEnglishResult) || !$insertEnglishResult[0]->ok) {
            $this->log(ResCode::COLLECTION_INSERT_FAIL);
            return $this->fail(ResCode::COLLECTION_INSERT_FAIL);
        }
        return $this->res();
    }

    public function updateEnglish() {
        $wordId = trim(strval(input('post.wordId')));
        $word = trim(strval(input('post.word')));
        $englishPhonetic = trim(strval(input('post.english_phonetic')));
        $americanPhonetic = trim(strval(input('post.american_phonetic')));
        $translation = input('post.translation/a');
        $exampleSentence = trim(strval(input('post.example_sentence')));
        $sentenceTranslation = trim(strval(input('post.sentence_translation')));
        $source = trim(strval(input('post.source')));
        if (!isset($word)) {
            $this->log(ResCode::MISSING_PARAMS_WORD);
            return $this->fail(ResCode::MISSING_PARAMS_WORD);
        }
        if (!isset($translation)) {
            $this->log(ResCode::MISSING_PARAMS_TRANSLATION);
            return $this->fail(ResCode::MISSING_PARAMS_TRANSLATION);
        }
        foreach ($translation as $t) {
            if (!is_array($t)) {
                $this->log(ResCode::ILLEGAL_ARGUMENT_TRANSLATION_ELEMENT);
                return $this->fail(ResCode::ILLEGAL_ARGUMENT_TRANSLATION_ELEMENT);
            }
            if (!isset($t['property']) || $t['property'] == '') {
                $this->log(ResCode::MISSING_PARAMS_PROPERTY);
                return $this->fail(ResCode::MISSING_PARAMS_PROPERTY);
            }
            if (!isset($t['property']) || $t['explanation'] == '') {
                $this->log(ResCode::MISSING_PARAMS_EXPLANATION);
                return $this->fail(ResCode::MISSING_PARAMS_EXPLANATION);
            }
        }

        if (!ctype_alnum($word)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_WORD);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_WORD);
        }
        $cmd = [
            'find' => 'english',
            'filter' => [
                'word' => $word,
            ],
            'projection' => [
                '_id' => 1
            ],
            'limit' => 1
        ];
        $wordInfo = Mongo::cmd($cmd);
        if ($wordInfo != null && $wordInfo[0]->_id != $wordId) {
            $this->log(ResCode::WORD_ALREADY_EXIST);
            return $this->fail(ResCode::WORD_ALREADY_EXIST);
        }

        $updateEnglishCmd = [
            'update' => 'english',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($wordId)
                    ],
                    'u' => [
                        '$set' => [
                            'word' => $word,
                            'english_phonetic' => $englishPhonetic,
                            'american_phonetic' => $americanPhonetic,
                            'translation' => $translation,//数组：property, explanation
                            'example_sentence' => $exampleSentence,
                            'sentence_translation' => $sentenceTranslation,
                            'source' => $source
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ]
        ];
        $updateResult = Mongo::cmd($updateEnglishCmd);
        if (!$updateResult[0]->ok) {
            return $this->fail(ResCode::COLLECTION_UPDATE_FAIL);
        }
        return $this->res();
    }

}