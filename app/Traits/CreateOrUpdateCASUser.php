<?php
/**
 * Created by PhpStorm.
 * User: ross
 * Date: 11/4/17
 * Time: 9:30 AM.
 */

namespace App\Traits;

use Log;
use App\Team;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

trait CreateOrUpdateCASUser
{
    protected $cas;

    public function __construct()
    {
        $this->cas = app('cas');
    }

    public function createOrUpdateCASUser(Request $request)
    {
        $attrs = ['email_primary', 'givenName', 'sn'];
        // Attributes that will be split by commas when masquerading
        $arrayAttrs = ['gtPersonEntitlement'];
        // Merge them together so we verify all attributes are present, even the array ones
        $attrs = array_merge($attrs, $arrayAttrs);
        if ($this->cas->isMasquerading()) {
            $masq_attrs = [];
            foreach ($attrs as $attr) {
                $masq_attrs[$attr] = config('cas.cas_masquerade_'.$attr);
            }
            // Split the attributes that we need to split
            foreach ($arrayAttrs as $attr) {
                $masq_attrs[$attr] = explode(',', $masq_attrs[$attr]);
            }
            $this->cas->setAttributes($masq_attrs);
        }

        foreach ($attrs as $attr) {
            if (! $this->cas->hasAttribute($attr) || $this->cas->getAttribute($attr) == null) {
                return response(view(
                    'errors.generic',
                    ['error_code' => 500,
                    'error_message' => 'Missing/invalid attributes from CAS',
                    ]
                ), 500);
            }
        }

        //User is starting a new session, so let's update data from CAS
        //Sadly we can't use updateOrCreate here because of $guarded in the User model
        $user = User::where('uid', $this->cas->user())->first();
        if ($user == null) {
            $user = new User();
        }
        $user->uid = $this->cas->user();
        $user->gt_email = $this->cas->getAttribute('email_primary');
        $user->first_name = $this->cas->getAttribute('givenName');
        $user->last_name = $this->cas->getAttribute('sn');
        $user->save();

        if (config('auth.apiary_endpoint')) {
            \Log::info('Loading information on '.$user->uid.' from Apiary');
            $client = new Client([
                'headers' => [
                    'User-Agent' => 'Onken on '.$_SERVER['SERVER_NAME'],
                    'Authorization' => 'Bearer '.config('auth.apiary_token'),
                    'Accept' => 'application/json',
                ],
                'http_errors' => false,
            ]);
            $response = $client->get(config('auth.apiary_endpoint').'/'.strtolower($user->uid).'?include=teams');
            $apiary_user = json_decode($response->getBody()->getContents(), true)['user'];

            if ($apiary_user != null) {
                if ($apiary_user['is_access_active'] && !$user->hasRole('viewer')) {
                    $user->assignRole('viewer');
                    \Log::info('Granted viewer role to active user '.$user->uid);
                } else if (!$apiary_user['is_access_active'] && $user->hasRole('viewer')) {
                    $user->removeRole('viewer');
                    \Log::info('Removed viewer role from inactive user '.$user->uid);
                }

                if ($apiary_user['preferred_first_name'] != null && $apiary_user['preferred_first_name'] != $user->first_name) {
                    $user->first_name = $apiary_user['preferred_first_name'];
                    $user->save();
                }
            }
        }

        return $user;
    }
}
