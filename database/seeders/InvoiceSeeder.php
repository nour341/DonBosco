<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // بيانات الفواتير
        $invoices = [
            [
                'task_id' => 1, // تدريس الرياضيات
                'number' => 1001,
                'from' => '2024-07-01',
                'to' => '2024-07-31',
                'status' => null ,
                'image_path' => 'path/to/image1.jpg',
                'items' => [
                    [
                        'name' => 'مكافأة تدريس الرياضيات',
                        'unite' => 'ساعة',
                        'itemBudget_id' => 3, // Salaries Wages
                        'unit_price' => 25,
                        'quantity' => 30,
                    ],
                ],
            ],
            [
                'task_id' => 2, // تدريس العلوم
                'number' => 1002,
                'from' => '2024-07-01',
                'to' => '2024-07-31',
                'status' => null,
                'image_path' => 'path/to/image2.jpg',
                'items' => [
                    [
                        'name' => 'مكافأة تدريس العلوم',
                        'unite' => 'ساعة',
                        'itemBudget_id' => 3, // Salaries Wages
                        'unit_price' => 25,
                        'quantity' => 30,
                    ],
                ],
            ],
            [
                'task_id' => 6, // تنظيم توزيع الطعام
                'number' => 1003,
                'from' => '2024-07-01',
                'to' => '2024-07-31',
                'status' => null,
                'image_path' => 'path/to/image3.jpg',
                'items' => [
                    [
                        'name' => 'شراء طعام',
                        'unite' => 'كيلوجرام',
                        'itemBudget_id' => 1, // Food
                        'unit_price' => 30,
                        'quantity' => 10,
                    ],
                ],
            ],
            [
                'task_id' => 7, // تنظيم توزيع المعدات
                'number' => 1004,
                'from' => '2024-07-01',
                'to' => '2024-07-31',
                'status' => null,
                'image_path' => 'path/to/image4.jpg',
                'items' => [
                    [
                        'name' => 'شراء معدات',
                        'unite' => 'قطعة',
                        'itemBudget_id' => 2, // Equipment
                        'unit_price' => 150,
                        'quantity' => 10,
                    ],
                ],
            ],
        ];

        foreach ($invoices as $invoiceData) {
            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'task_id' => $invoiceData['task_id'],
                'number' => $invoiceData['number'],
                'from' => $invoiceData['from'],
                'to' => $invoiceData['to'],
                'status' => $invoiceData['status'],
                'total_price' => 0, // سيتم تحديثه لاحقاً
            ]);

            $totalInvoiceSum = 0;

            // إضافة الصورة
            $file_path = $invoiceData['image_path'];
            $file_type = 'image'; // يمكنك تحديثها حسب نوع الملف الحقيقي
            $filename = basename($file_path);

            $file = File::create([
                'name' => $filename,
                'folder_id' => 1, // تأكد من تطابق هذا الـ id مع المجلد الموجود في قاعدة البيانات
                'type' => $file_type,
                'description' => 'صورة الفاتورة',
            ]);

            $invoice->image_id = $file->id;

            foreach ($invoiceData['items'] as $item) {
                $total_price = $item['quantity'] * $item['unit_price'];

                // إنشاء عنصر الفاتورة
                InvoiceItem::create([
                    'name' => $item['name'],
                    'itemBudget_id' => $item['itemBudget_id'],
                    'invoice_id' => $invoice->id,
                    'unite' => $item['unite'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'total_price_quantity' => $total_price,
                ]);

                $totalInvoiceSum += $total_price;
            }

            // تحديث السعر الإجمالي للفاتورة
            $invoice->total_price = $totalInvoiceSum;
            $invoice->save();
        }
    }
}
