<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Access]].
 *
 * @see \app\models\Access
 */
class AccessQuery extends \yii\db\ActiveQuery
{
     /**
     * Condition with User Guest ID
     * @param $id
     * @return $this
     */
    public function withUserGuest($id)
    {
        return $this->andWhere('user_guest = :id', [":id" => $id]);
    }

    /**
     * Condition with User Owner ID
     * @param $id
     * @return $this
     */
    public function withUserOwner($id)
    {
        return $this->andWhere('user_owner = :id', [":id" => $id]);
    }

    /**
     * Condition with Date
     * @param $date
     * @return $this
     */
    
    public function withSharedDate($date)
    {
        return $this->andWhere('date = :date', [":date" => $date]);
    }

    /**
     * @inheritdoc
     * @return \app\models\Access[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Access|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
