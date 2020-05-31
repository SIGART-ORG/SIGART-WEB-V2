<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Presentation extends Model
{
    const TABLE_NAME = 'presentation';
    const TABLE_NAME_PRODUCT = 'products';
    const TABLE_NAME_CATEGORY = 'categories';
    const TABLE_NAME_UNITY = 'unity';

    protected $table = self::TABLE_NAME;

    protected $fillable = [
        'sites_id',
        'products_id',
        'unity_id',
        'sku',
        'slug',
        'description',
        'status'
    ];

    public function product() {
        return $this->belongsTo( Product::class, 'products_id', 'id' );
    }

    public function brand() {
        return $this->belongsTo( Brand::class, 'brands_id', 'id' );
    }

    public function listData() {

        $data = DB::table( self::TABLE_NAME )
            ->join( self::TABLE_NAME_PRODUCT, self::TABLE_NAME_PRODUCT . '.id', '=', self::TABLE_NAME . '.products_id' )
            ->join( self::TABLE_NAME_CATEGORY, self::TABLE_NAME_CATEGORY . '.id', '=', self::TABLE_NAME_PRODUCT . '.category_id' )
            ->join( self::TABLE_NAME_UNITY, self::TABLE_NAME_UNITY . '.id', '=', self::TABLE_NAME . '.unity_id' )
            ->where( self::TABLE_NAME . '.status', 1 )
            ->where( self::TABLE_NAME_PRODUCT . '.status', 1 )
            ->where( self::TABLE_NAME_CATEGORY . '.status', 1 )
            ->where( self::TABLE_NAME_UNITY . '.status', 1 )
            ->select(
                self::TABLE_NAME . '.id',
                self::TABLE_NAME . '.sku',
                self::TABLE_NAME . '.slug',
                self::TABLE_NAME . '.description as name',
                self::TABLE_NAME_PRODUCT . '.name as product',
                self::TABLE_NAME_CATEGORY . '.name as category',
                self::TABLE_NAME_UNITY . '.name as unity'
            )
            ->get();

        return $data;
    }
}
