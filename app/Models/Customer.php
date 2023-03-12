<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    static function countCustomers()
    {
        return Customer::all()->count();
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function contacts()
    {
        return $this->hasMany(CustomerContact::class);
    }

    public function calls()
    {
        return $this->hasMany(Call::class, 'customer_id', 'id')->withTrashed();
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function getMainAddress()
    {
        foreach ($this->addresses as $address) {
            if ($address->status == 'Hoofdlocatie') {
                return $address->address . ', ' . $address->zip_code . ' ' . $address->place;
            } else {
                return $address->address . ', ' . $address->zip_code . ' ' . $address->place;
            }
        }
        return false;
    }

    public function getMainEmail()
    {
        foreach ($this->contacts as $contact) {
            if ($contact->function == 'Eigenaar') {
                return $contact->email;
            } else {
                return $contact->email;
            }
        }
        return false;
    }

    public function getMainPhone()
    {
        foreach ($this->contacts as $contact) {
            if ($contact->function == 'Eigenaar') {
                return $contact->phone;
            } else {
                return $contact->phone;
            }
        }
        return false;
    }

    public function countAddresses()
    {
        return $this->addresses->count();
    }

    public function countContacts()
    {
        return $this->contacts->count();
    }

    public function countProjects()
    {
        return 0;
    }

    public function getMainLocation()
    {
        return $this->addresses->where('status', 'Hoofdlocatie')->first();
    }

    public function hasMainLocation()
    {
        if ($this->addresses->where('status', 'Hoofdlocatie')->first() != null) {
            return true;
        } else {
            return false;
        }
    }

    public function getOwner()
    {
        return $this->contacts->where('status', 'Eigenaar')->first();
    }

    public function hasOwner()
    {
        if ($this->contacts->where('status', 'Eigenaar')->first() != null) {
            return true;
        } else {
            return false;
        }
    }

    public function getYearlyProjects($year)
    {
        $projectIds = Project::where('customer_id', $this->id)->distinct('id')->pluck('id')->toArray();
        $workOrders = WorkOrder::where('status', 'Jaarfactuur')->whereIn('project_id', $projectIds)->whereyear('date', $year)->distinct('project_id')->pluck('project_id')->toArray();

        return Project::whereIn('id', $workOrders)->get();
    }
}
