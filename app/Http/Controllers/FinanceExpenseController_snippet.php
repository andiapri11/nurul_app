
    public function uploadGeneralProof(Request $request, $id)
    {
        $request->validate([
            'nota' => 'required|image|max:2048',
        ]);

        $expense = IncomeExpense::findOrFail($id);
        
        $path = $request->file('nota')->store('expenses/proofs', 'public');
        
        $expense->update([
            'nota' => $path,
            'proof_status' => 'Reported'
        ]);
        
        return back()->with('success', 'Bukti nota berhasil diupload.');
    }
