<?php

/**
 * implementing search based on Auth class
 *
 * @author Mustafa Magdi <developer.mustafa@gmail.com>
 * @link https://github.com/devmustafa/yii2-blog-mongodb
 */

namespace devmustafa\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AuthorSearch represents the model behind the search form about `\devmustafa\blog\models\Author`.
 */
class AuthorSearch extends Author
{
    /**
     * @return array of the collection rules
     */
    public function rules()
    {
        return [
            [['_id', 'id', 'name', 'bio', 'url', 'img', 'is_active'], 'safe'],
        ];
    }

    /**
     * @return array of scenarios
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Author::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'img', $this->img])
            ->andFilterWhere(['like', 'is_active', $this->is_active]);

        // set default language
        $module = Yii::$app->getModule('blog');
        $default_language = $module->default_language;

        // search in deep docs
        foreach ($this->langAttributes() as $attribute => $type){
            $query->andFilterWhere(['like', "{$attribute}.{$default_language}", $this->{$attribute}]);
        }

        return $dataProvider;
    }
}
