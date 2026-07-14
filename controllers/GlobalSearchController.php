<?php
class GlobalSearchController {

    public function search(): void {
        Auth::requireLogin();
        $q = trim($_GET['q'] ?? '');
        if (strlen($q) < 2) { View::success([]); }

        $like    = '%' . $q . '%';
        $results = [];
        $limit   = 6;

        // Clients
        try {
            $rows = DB::all(
                "SELECT id, name, email FROM bh_clients
                  WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?
                  ORDER BY name LIMIT {$limit}",
                [$like, $like, $like]
            );
            foreach ($rows as $r) {
                $results[] = ['type'=>'client','label'=>$r->name,'sub'=>$r->email??'','url'=>BH_APP_URL.'/clients/'.$r->id];
            }
        } catch (\Throwable $e) {}

        // Tickets
        try {
            $rows = DB::all(
                "SELECT t.id, t.subject, c.name AS cname FROM bh_tickets t
                  LEFT JOIN bh_clients c ON c.id = t.client_id
                  WHERE t.subject LIKE ? OR t.id LIKE ?
                  ORDER BY t.id DESC LIMIT {$limit}",
                [$like, $like]
            );
            foreach ($rows as $r) {
                $results[] = ['type'=>'ticket','label'=>'#'.$r->id.' '.$r->subject,'sub'=>$r->cname??'','url'=>BH_APP_URL.'/tickets/'.$r->id];
            }
        } catch (\Throwable $e) {}

        // Invoices
        try {
            $rows = DB::all(
                "SELECT i.id, i.invoice_number, i.amount, i.status, c.name AS cname FROM bh_invoices i
                  LEFT JOIN bh_clients c ON c.id = i.client_id
                  WHERE i.invoice_number LIKE ? OR c.name LIKE ?
                  ORDER BY i.id DESC LIMIT {$limit}",
                [$like, $like]
            );
            foreach ($rows as $r) {
                $num = $r->invoice_number ?: 'INV-'.str_pad($r->id,5,'0',STR_PAD_LEFT);
                $results[] = ['type'=>'invoice','label'=>$num.' — $'.number_format((float)$r->amount,2),'sub'=>$r->cname??'','url'=>BH_APP_URL.'/invoices/'.$r->id];
            }
        } catch (\Throwable $e) {}

        // Contacts
        try {
            $rows = DB::all(
                "SELECT cc.id, cc.name, cc.email, c.name AS cname, c.id AS cid FROM bh_client_contacts cc
                  LEFT JOIN bh_clients c ON c.id = cc.client_id
                  WHERE cc.name LIKE ? OR cc.email LIKE ? OR cc.phone LIKE ?
                  LIMIT {$limit}",
                [$like, $like, $like]
            );
            foreach ($rows as $r) {
                $results[] = ['type'=>'contact','label'=>$r->name,'sub'=>$r->cname.' — '.($r->email??''),'url'=>BH_APP_URL.'/clients/'.$r->cid.'#tab-contacts'];
            }
        } catch (\Throwable $e) {}

        // KB articles
        try {
            $rows = DB::all(
                "SELECT id, title FROM bh_kb_articles WHERE title LIKE ? OR content LIKE ? ORDER BY id DESC LIMIT 4",
                [$like, $like]
            );
            foreach ($rows as $r) {
                $results[] = ['type'=>'kb','label'=>$r->title,'sub'=>'Knowledge Base','url'=>BH_APP_URL.'/kb/article/'.$r->id];
            }
        } catch (\Throwable $e) {}

        View::success($results);
    }
}
