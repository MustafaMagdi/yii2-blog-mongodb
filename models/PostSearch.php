<?php

namespace devmustafa\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostSearch represents the model behind the search form about `devmustafa\blog\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'id', 'author_id', 'category_id', 'views', 'is_published', 'is_notification_sent', 'publish_date', 'created_at', 'updated_at', 'title', 'slug', 'intro', 'body', 'tags', 'meta_keywords', 'meta_description', 'image_origin', 'image_thumb'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Post::find();

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
            ->andFilterWhere(['like', 'author_id', $this->author_id])
            ->andFilterWhere(['like', 'category_id', $this->category_id])
            ->andFilterWhere(['like', 'views', $this->views])
            ->andFilterWhere(['like', 'is_published', $this->is_published])
            ->andFilterWhere(['like', 'is_notification_sent', $this->is_notification_sent])
            ->andFilterWhere(['like', 'publish_date', $this->publish_date])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        // set default language
        $module = Yii::$app->getModule('blog');
        $default_language = $module->default_language;

        // search in deep docs
        foreach ($this->langAttributes() as $attribute => $type){
            $query->andFilterWhere(['like', "{$attribute}.{$default_language}", $this->$attribute]);
        }

        return $dataProvider;
    }
}
