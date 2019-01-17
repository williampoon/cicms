<?php

/**
 * 身份证校验类
 *
 * @author 潘伟麟 <249865225@qq.com>
 */
class IDCard
{
    // 校验身份证号码
    public function validate($id)
    {
        if (strlen($id) === 18) {
            return $this->validate18($id);

        } elseif (strlen($id) === 15) {
            return $this->validate15($id);

        } else {
            return false;

        }
    }

    // 校验18位身份证
    public function validate18($id)
    {
        if (strlen($id) != 18 || !preg_match('/^\d{17}/', $id)) {
            return false;
        }

        return substr($id, 17) === $this->getVerifyNum(substr($id, 0, 17));
    }

    // 校验15位身份证
    public function validate15($id)
    {
        if (!preg_match('/^\d{15}$/', $id)) {
            return false;
        }

        $year  = intval(substr($id, 6, 2));
        $month = intval(substr($id, 8, 2));
        $day   = intval(substr($id, 10, 2));

        if ($year < 1 || $year > 90) {
            return false;
        }
        if ($month < 1 || $month > 12) {
            return false;
        }
        if ($day < 1 || $day > 31) {
            return false;
        }

        return true;
    }

    // 身份证15位升18位
    public function transform15to18($id)
    {
        if (!preg_match('/^\d{15}$/', $id)) {
            return '';
        }

        $tmp = substr($id, 0, 6) . '19' . substr($id, 6);

        return $tmp . $this->getVerifyNum($tmp);
    }

    // 获取性别（只适用于15位身份证）
    public function getGender($id)
    {
        if (!preg_match('/^\d{15}$/', $id)) {
            return '';
        }

        $num    = substr($id, 14);
        $gender = $num % 2 === 0 ? 'female' : 'male';

        return $gender;
    }

    // 按照国家标准 GB 11643-1999，根据身份证前17位生成检验码（尾号）
    protected function getVerifyNum($id)
    {
        if (!preg_match('/^\d{17}$/', $id)) {
            return '';
        }

        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        $sum    = 0;
        foreach (str_split($id) as $key => $num) {
            $sum += $num * $factor[$key];
        }
        $remainder = $sum % 11;

        // 校验码映射
        $map = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');

        return $map[$remainder];
    }
}
