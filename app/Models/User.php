<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin Builder
 *
 * @property int                    $id
 * @property string                 $name
 * @property string                 $email
 * @property DateTime               $email_verified_at
 * @property string                 $password
 * @property int                    $staff_id
 * @property Staff                  $staff
 * @property int                    $current_team_id
 * @property string                 $profile_photo_path
 * @property string                 $role
 * @property string                 $auth_phone
 * @property int                    $company_id
 * @property Company                $company
 * @property bool                   $uses2fa
 * @property string                 $auth_code
 * @property bool                   $active
 * @property string                 $backup_codes
 * @property int                    $max_idle_time
 * @property string                 $remember_token
 * @property DateTime               $created_at
 * @property DateTime               $updated_at
 * @property DateTime                   $deleted_at
 * @property string                     $two_factor_secret
 * @property string                     $two_factor_recovery_codes
 *
 * @property Deployment[]               $billedDeployments
 * @property Asset[]                    $createdAssets
 * @property Asset[]                    $updatedAssets
 * @property SaleOrder[]                $createdSaleOrders
 * @property SaleOrder[]                $updatedSaleOrders
 * @property SaleOrderProduct[]         $createdSaleOrderProducts
 * @property SaleOrderProduct[]         $updatedSaleOrderProducts
 * @property PurchaseOrder[]            $createdPurchaseOrders
 * @property PurchaseOrder[]            $updatedPurchaseOrders
 * @property PurchaseOrderProduct[]     $createdPurchaseOrderProducts
 * @property PurchaseOrderProduct[]     $updatedPurchaseOrderProducts
 * @property Category[]                 $createdCategories
 * @property Category[]                 $updatedCategories
 * @property Customer[]                 $createdCustomers
 * @property Customer[]                 $updatedCustomers
 * @property Deployment[]               $createdDeployments
 * @property Deployment[]               $updatedDeployments
 * @property Document[]                 $createdDocuments
 * @property Document[]                 $updatedDocuments
 * @property HasDocument[]              $createdHasDocuments
 * @property Supplier[]                 $createdSuppliers
 * @property Supplier[]                 $updatedSuppliers
 * @property ProductSlot[]              $createdProductSlot
 * @property ProductSlot[]              $updatedProductSlot
 * @property DeploymentSlot[]           $createdDeploymentSlot
 * @property DeploymentSlot[]           $updatedDeploymentSlot
 * @property DeploymentSlotConnection[] $createdDeploymentSlotConnections
 * @property DeploymentSlotConnection[] $updatedDeploymentSlotConnections
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $guarded = [];

    /**
     * @return Company|BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * @return Staff|BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'stuff_id', 'id');
    }

    /**
     * @return Deployment[]|HasMany
     */
    public function billedDeployments(): HasMany
    {
        return $this->hasMany(Deployment::class, 'billed_by_id', 'id');
    }

    /**
     * @return SaleOrder[]|HasMany
     */
    public function createdAssets(): HasMany
    {
        return $this->hasMany(Asset::class, 'created_by_id', 'id');
    }

    /**
     * @return SaleOrder[]|HasMany
     */
    public function updatedAssets(): HasMany
    {
        return $this->hasMany(Asset::class, 'updated_by_id', 'id');
    }

    /**
     * @return SaleOrder[]|HasMany
     */
    public function createdSaleOrders(): HasMany
    {
        return $this->hasMany(SaleOrder::class, 'created_by_id', 'id');
    }

    /**
     * @return SaleOrder[]|HasMany
     */
    public function updatedSaleOrders(): HasMany
    {
        return $this->hasMany(SaleOrder::class, 'updated_by_id', 'id');
    }

    /**
     * @return SaleOrderProduct[]|HasMany
     */
    public function createdSaleOrderProducts(): HasMany
    {
        return $this->hasMany(SaleOrderProduct::class, 'created_by_id', 'id');
    }

    /**
     * @return SaleOrderProduct[]|HasMany
     */
    public function updatedSaleOrderProducts(): HasMany
    {
        return $this->hasMany(SaleOrderProduct::class, 'updated_by_id', 'id');
    }

    /**
     * @return PurchaseOrder[]|HasMany
     */
    public function createdPurchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'created_by_id', 'id');
    }

    /**
     * @return PurchaseOrder[]|HasMany
     */
    public function updatedPurchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'updated_by_id', 'id');
    }

    /**
     * @return PurchaseOrderProduct[]|HasMany
     */
    public function createdPurchaseOrderProducts(): HasMany
    {
        return $this->hasMany(PurchaseOrderProduct::class, 'created_by_id', 'id');
    }

    /**
     * @return PurchaseOrderProduct[]|HasMany
     */
    public function updatedPurchaseOrderProducts(): HasMany
    {
        return $this->hasMany(PurchaseOrderProduct::class, 'updated_by_id', 'id');
    }

    /**
     * @return Category[]|HasMany
     */
    public function createdCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'created_by_id', 'id');
    }

    /**
     * @return Category[]|HasMany
     */
    public function updatedCategories(): HasMany
    {
        return $this->hasMany(Category::class, 'updated_by_id', 'id');
    }

    /**
     * @return Customer[]|HasMany
     */
    public function createdCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'created_by_id', 'id');
    }

    /**
     * @return Customer[]|HasMany
     */
    public function updatedCustomers(): HasMany
    {
        return $this->hasMany(Customer::class, 'updated_by_id', 'id');
    }

    /**
     * @return Deployment[]|HasMany
     */
    public function createdDeployments(): HasMany
    {
        return $this->hasMany(Deployment::class, 'created_by_id', 'id');
    }

    /**
     * @return Deployment[]|HasMany
     */
    public function updatedDeployments(): HasMany
    {
        return $this->hasMany(Deployment::class, 'updated_by_id', 'id');
    }

    /**
     * @return Document[]|HasMany
     */
    public function createdDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'created_by_id', 'id');
    }

    /**
     * @return Document[]|HasMany
     */
    public function updatedDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'updated_by_id', 'id');
    }

    /**
     * @return HasDocument[]|HasMany
     */
    public function createdHasDocuments(): HasMany
    {
        return $this->hasMany(HasDocument::class, 'created_by_id', 'id');
    }

    /**
     * @return HasDocument[]|HasMany
     */
    public function updatedHasDocuments(): HasMany
    {
        return $this->hasMany(HasDocument::class, 'updated_by_id', 'id');
    }

    /**
     * @return Supplier[]|HasMany
     */
    public function createdSuppliers(): HasMany
    {
        return $this->hasMany(Supplier::class, 'created_by_id', 'id');
    }

    /**
     * @return Supplier[]|HasMany
     */
    public function updatedSuppliers(): HasMany
    {
        return $this->hasMany(Supplier::class, 'updated_by_id', 'id');
    }

    /**
     * @return ProductSlot[]|HasMany
     */
    public function createdProductSlots(): HasMany
    {
        return $this->hasMany(ProductSlot::class, 'created_by_id', 'id');
    }

    /**
     * @return ProductSlot[]|HasMany
     */
    public function updatedProductSlots(): HasMany
    {
        return $this->hasMany(ProductSlot::class, 'updated_by_id', 'id');
    }

    /**
     * @return DeploymentSlot[]|HasMany
     */

    public function createdDeploymentSlots(): HasMany
    {
        return $this->hasMany(DeploymentSlot::class, 'created_by_id', 'id');
    }

    /**
     * @return DeploymentSlot[]|HasMany
     */
    public function updatedDeplomentSlots(): HasMany
    {
        return $this->hasMany(DeploymentSlot::class, 'updated_by_id', 'id');
    }

    /**
     * @return DeploymentSlotConnection[]|HasMany
     */
    public function createdDeploymentSlotConnections(): HasMany
    {
        return $this->hasMany(DeploymentSlotConnection::class, 'created_by_id', 'id');
    }

    /**
     * @return DeploymentSlotConnection[]|HasMany
     */
    public function updatedDeploymentSlotConnections(): HasMany
    {
        return $this->hasMany(DeploymentSlotConnection::class, 'updated_by_id', 'id');
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role == 'admin';
    }
}
