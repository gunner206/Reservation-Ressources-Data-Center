<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    \App\Models\Resource::create([
        'name' => 'Dell PowerEdge R740',
        'code' => 'SRV-DELL-01',
        'category_id' => 1, 
        'is_active' => true,
        'specs' => json_encode(['cpu' => 'Intel Xeon', 'ram' => '128GB'])
    ]);

    \App\Models\Resource::create([
        'name' => 'Baie NetApp AFF A400',
        'code' => 'STO-NET-01',
        'category_id' => 2,
        'is_active' => true,
        'specs' => json_encode(['capacity' => '20TB', 'type' => 'SSD'])
    ]);

    \App\Models\Resource::create([
        'name' => 'Cisco Catalyst 9300',
        'code' => 'SW-CIS-01',
        'category_id' => 3,
        'is_active' => true,
        'specs' => json_encode(['ports' => '48', 'speed' => '10Gbps'])
    ]);

    \App\Models\Resource::create([
        'name' => 'Firewall FortiGate 100F',
        'code' => 'FW-FORT-01',
        'category_id' => 4,
        'is_active' => true,
        'specs' => json_encode(['throughput' => '20Gbps', 'vpn' => 'SSL/IPsec'])
    ]);

    \App\Models\Resource::create([
        'name' => 'Cluster VMware ESXi',
        'code' => 'VIRT-VMW-01',
        'category_id' => 5,
        'is_active' => true,
        'specs' => json_encode(['nodes' => '3', 'hypervisor' => 'vSphere 8'])
    ]);
}
}


