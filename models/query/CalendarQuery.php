<?php

namespace app\models\query;


/**
 * This is the ActiveQuery class for [[\app\models\Calendar]].
 *
 * @see \app\models\Calendar
 */
class CalendarQuery extends \yii\db\ActiveQuery
{
   /**
     * With Date for checking DATETIME by DATE
     *
     * @param $date
     * @return $this
     */
    public function withDate($date)
    {
        return $this->andWhere('date_event_start LIKE :date', [':date' => $date.'%']);
    }

    /**
     * With Creator
     *
     * @param $id
     * @return $this
     */
    public function withCreator($id){
        return $this->andWhere('creator = :id', [':id' => $id]);
    }

    /**
     * @inheritdoc
     * @return \app\models\Calendar[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Calendar|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
