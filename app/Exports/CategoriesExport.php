<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CategoriesExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Category::all();
    }

    /**
     * Map each category to an array with the desired fields.
     *
     * @param  \App\Models\Category  $category
     * @return array
     */
    public function map($category): array
    {
        return [
            $category->name,
            $category->created_at,
            $category->updated_at,
        ];
    }

    /**
     * Define the headings for your export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Created At',
            'Updated At',
        ];
    }
}
