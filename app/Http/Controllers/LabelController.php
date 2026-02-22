<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
use App\Models\Label;

class LabelController extends Controller
{
    public function index()
    {
        $labels = auth()->user()->labels()->withCount('tasks')->orderBy('name')->get();
        return view('labels.index', compact('labels'));
    }

    public function store(StoreLabelRequest $request)
    {
        $this->authorize('create', Label::class);

        auth()->user()->labels()->create($request->validated());

        return redirect()->route('labels.index')
            ->with('success', 'Label created.');
    }

    public function update(UpdateLabelRequest $request, Label $label)
    {
        $this->authorize('update', $label);

        $label->update($request->validated());

        return redirect()->route('labels.index')
            ->with('success', 'Label updated.');
    }

    public function destroy(Label $label)
    {
        $this->authorize('delete', $label);

        $label->delete(); // pivot rows cascade

        return redirect()->route('labels.index')
            ->with('success', 'Label deleted.');
    }
}
