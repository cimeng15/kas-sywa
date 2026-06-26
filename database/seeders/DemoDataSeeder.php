<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Debt;
use App\Models\Notification;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $orangTua = User::firstOrCreate(
            ['email' => 'keluarga@example.com'],
            [
                'name' => 'Bapak Keluarga',
                'username' => 'bapak',
                'password' => bcrypt('password'),
                'role' => 'orang_tua',
            ]
        );

        $anak1 = User::firstOrCreate(
            ['email' => 'anak1@example.com'],
            [
                'name' => 'Anak Pertama',
                'username' => 'anak1',
                'password' => bcrypt('password'),
                'role' => 'anak',
                'parent_id' => $orangTua->id,
            ]
        );

        $anak2 = User::firstOrCreate(
            ['email' => 'anak2@example.com'],
            [
                'name' => 'Anak Kedua',
                'username' => 'anak2',
                'password' => bcrypt('password'),
                'role' => 'anak',
                'parent_id' => $orangTua->id,
            ]
        );

        $incomeCategories = [
            ['name' => 'Gaji', 'icon' => '💼', 'color' => '#10B981', 'type' => 'income'],
            ['name' => 'Bonus', 'icon' => '🎁', 'color' => '#6366F1', 'type' => 'income'],
            ['name' => 'Uang Saku', 'icon' => '💵', 'color' => '#22C55E', 'type' => 'income'],
            ['name' => 'Lainnya', 'icon' => '📥', 'color' => '#8B5CF6', 'type' => 'income'],
        ];

        $expenseCategories = [
            ['name' => 'Makan & Minum', 'icon' => '🍽️', 'color' => '#EF4444', 'type' => 'expense'],
            ['name' => 'Transportasi', 'icon' => '🚗', 'color' => '#F59E0B', 'type' => 'expense'],
            ['name' => 'Listrik & Air', 'icon' => '⚡', 'color' => '#EC4899', 'type' => 'expense'],
            ['name' => 'Belanja', 'icon' => '🛒', 'color' => '#14B8A6', 'type' => 'expense'],
            ['name' => 'Pendidikan', 'icon' => '📚', 'color' => '#3B82F6', 'type' => 'expense'],
            ['name' => 'Kesehatan', 'icon' => '🏥', 'color' => '#F97316', 'type' => 'expense'],
            ['name' => 'Hiburan', 'icon' => '🎮', 'color' => '#A855F7', 'type' => 'expense'],
        ];

        $categoryModels = [];
        foreach (array_merge($incomeCategories, $expenseCategories) as $cat) {
            $cat['user_id'] = $orangTua->id;
            $key = $cat['name'];
            unset($cat['name']);
            $cat['name'] = $key;
            $categoryModels[$cat['name']] = Category::firstOrCreate(
                ['user_id' => $orangTua->id, 'name' => $cat['name']],
                $cat
            );
        }

        $transactionsOrangTua = [
            ['type' => 'income', 'amount' => 5000000, 'description' => 'Gaji bulan Juni', 'category' => 'Gaji', 'date' => '2026-06-01'],
            ['type' => 'expense', 'amount' => 500000, 'description' => 'Belanja mingguan', 'category' => 'Belanja', 'date' => '2026-06-03'],
            ['type' => 'expense', 'amount' => 150000, 'description' => 'Makan di restoran', 'category' => 'Makan & Minum', 'date' => '2026-06-05'],
            ['type' => 'expense', 'amount' => 300000, 'description' => 'Bensin mobil', 'category' => 'Transportasi', 'date' => '2026-06-07'],
            ['type' => 'expense', 'amount' => 450000, 'description' => 'Bayar listrik', 'category' => 'Listrik & Air', 'date' => '2026-06-10'],
            ['type' => 'expense', 'amount' => 750000, 'description' => 'SPP sekolah anak', 'category' => 'Pendidikan', 'date' => '2026-06-12'],
            ['type' => 'income', 'amount' => 1000000, 'description' => 'Bonus proyek', 'category' => 'Bonus', 'date' => '2026-06-15'],
            ['type' => 'expense', 'amount' => 200000, 'description' => 'Beli obat', 'category' => 'Kesehatan', 'date' => '2026-06-18'],
            ['type' => 'expense', 'amount' => 350000, 'description' => 'Belanja bulanan', 'category' => 'Belanja', 'date' => '2026-06-20'],
            ['type' => 'expense', 'amount' => 100000, 'description' => 'Makan siang', 'category' => 'Makan & Minum', 'date' => '2026-06-22'],
        ];

        foreach ($transactionsOrangTua as $trx) {
            Transaction::create([
                'user_id' => $orangTua->id,
                'category_id' => $categoryModels[$trx['category']]->id,
                'type' => $trx['type'],
                'amount' => $trx['amount'],
                'description' => $trx['description'],
                'date' => $trx['date'],
            ]);
        }

        $transactionsAnak1 = [
            ['type' => 'income', 'amount' => 50000, 'description' => 'Uang saku mingguan', 'category' => 'Uang Saku', 'date' => '2026-06-01'],
            ['type' => 'expense', 'amount' => 15000, 'description' => 'Beli jajan', 'category' => 'Makan & Minum', 'date' => '2026-06-02'],
            ['type' => 'expense', 'amount' => 20000, 'description' => 'Beli pulsa', 'category' => 'Lainnya', 'date' => '2026-06-05'],
            ['type' => 'expense', 'amount' => 30000, 'description' => 'Nonton bioskop', 'category' => 'Hiburan', 'date' => '2026-06-08'],
            ['type' => 'expense', 'amount' => 10000, 'description' => 'Es krim', 'category' => 'Makan & Minum', 'date' => '2026-06-10'],
        ];

        foreach ($transactionsAnak1 as $trx) {
            Transaction::create([
                'user_id' => $anak1->id,
                'category_id' => $categoryModels[$trx['category']]->id,
                'type' => $trx['type'],
                'amount' => $trx['amount'],
                'description' => $trx['description'],
                'date' => $trx['date'],
            ]);
        }

        $transactionsAnak2 = [
            ['type' => 'income', 'amount' => 40000, 'description' => 'Uang saku', 'category' => 'Uang Saku', 'date' => '2026-06-01'],
            ['type' => 'expense', 'amount' => 10000, 'description' => 'Beli pensil', 'category' => 'Pendidikan', 'date' => '2026-06-03'],
            ['type' => 'expense', 'amount' => 25000, 'description' => 'Main game', 'category' => 'Hiburan', 'date' => '2026-06-06'],
        ];

        foreach ($transactionsAnak2 as $trx) {
            Transaction::create([
                'user_id' => $anak2->id,
                'category_id' => $categoryModels[$trx['category']]->id,
                'type' => $trx['type'],
                'amount' => $trx['amount'],
                'description' => $trx['description'],
                'date' => $trx['date'],
            ]);
        }

        $debt1 = Debt::firstOrCreate(
            ['user_id' => $orangTua->id, 'person_name' => 'Bank BCA - KPR'],
            [
                'type' => 'utang',
                'payment_type' => 'cicilan_tetap',
                'total_amount' => 12000000,
                'remaining_amount' => 9000000,
                'installment_amount' => 1000000,
                'due_date' => '2026-07-01',
                'note' => 'Cicilan KPR bulanan 12x',
                'status' => 'belum_lunas',
            ]
        );

        $debt2 = Debt::firstOrCreate(
            ['user_id' => $orangTua->id, 'person_name' => 'Budi (teman kantor)'],
            [
                'type' => 'piutang',
                'payment_type' => 'bebas',
                'total_amount' => 2500000,
                'remaining_amount' => 2500000,
                'installment_amount' => null,
                'due_date' => '2026-06-30',
                'note' => 'Pinjaman untuk biaya pengobatan',
                'status' => 'belum_lunas',
            ]
        );

        $debt3 = Debt::firstOrCreate(
            ['user_id' => $orangTua->id, 'person_name' => 'Dealer Motor - Honda Beat'],
            [
                'type' => 'utang',
                'payment_type' => 'cicilan_tetap',
                'total_amount' => 18000000,
                'remaining_amount' => 15000000,
                'installment_amount' => 1500000,
                'due_date' => '2026-06-25',
                'note' => 'Cicilan motor 12x',
                'status' => 'belum_lunas',
            ]
        );

        $debt4 = Debt::firstOrCreate(
            ['user_id' => $orangTua->id, 'person_name' => 'Toko Elektronik - TV'],
            [
                'type' => 'utang',
                'payment_type' => 'bebas',
                'total_amount' => 3000000,
                'remaining_amount' => 1500000,
                'installment_amount' => null,
                'due_date' => '2026-06-25',
                'note' => 'Utang TV, bayar bebas',
                'status' => 'belum_lunas',
            ]
        );

        Notification::firstOrCreate(
            ['user_id' => $orangTua->id, 'debt_id' => $debt1->id, 'type' => 'warning'],
            [
                'title' => 'Jatuh tempo KPR',
                'message' => 'Cicilan KPR Bank BCA sebesar Rp 12.000.000 akan jatuh tempo pada 1 Juli 2026.',
            ]
        );

        Notification::firstOrCreate(
            ['user_id' => $orangTua->id, 'debt_id' => $debt3->id, 'type' => 'due_date'],
            [
                'title' => 'Jatuh tempo hari ini!',
                'message' => 'Cicilan TV Toko Elektronik sebesar Rp 1.500.000 jatuh tempo hari ini.',
            ]
        );

        Notification::firstOrCreate(
            ['user_id' => $orangTua->id, 'debt_id' => $debt2->id, 'type' => 'warning'],
            [
                'title' => 'Piutang jatuh tempo',
                'message' => 'Piutang dari Budi sebesar Rp 2.500.000 akan jatuh tempo pada 30 Juni 2026.',
            ]
        );
    }
}
