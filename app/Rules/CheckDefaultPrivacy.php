<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\FriendRequest;

class CheckDefaultPrivacy implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //convert json request to array
        $array = json_decode($value, true);
        $statusFieldIsExist = isset($array['status']);
        $idListIsExist = isset($array['id_list']);

        if (!$statusFieldIsExist) {
            //return false if status field is missing
            return false;
        } else {
            //validating status field
            if ($array['status'] != 'private' && $array['status'] != 'public' && $array['status'] != 'friends' && $array['status'] != 'custom') {
                return false;
            }
            //returns false if it has an id_list with non custom status 
            if ($array['status'] != 'custom' && $idListIsExist) {
                return false;
            }
            //return false if the status is custom and not having id_list of custom viewers
            if ($array['status'] == 'custom' && !$idListIsExist) {
                return false;
            }
            //validating id_list
            if ($idListIsExist) {
                //check if the id_list is an array
                if (!is_array($array['id_list'])) {
                    return false;
                }
                //check if the length of id_list array is zero
                if (count($array['id_list']) == 0) {
                    return false;
                }
                //check if id_list array is unique
                $length = count($array['id_list']);
                $supposedLength = count(array_unique($array['id_list']));
                if ($length != $supposedLength) {
                    return false;
                }
                //checking id's in id_list array
                foreach ($array['id_list'] as $id) {
                    //checking if the id exists in users table
                    if (!User::find($id)) {
                        return false;
                    }
                    //checking if the id refers to the same authenticated user
                    if (User::isSame(Auth::id(), $id)) {
                        return false;
                    }
                    //checking if the id belongs to authenticated friend
                    if (!FriendRequest::isFriend($id)) {
                        return false;
                    }

                }
            }
        }

        return true;


    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'wrong default_privacy field';
    }
}
