<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
use App\Models\Label;
use App\Repositories\LabelRepository;
use App\Services\LabelCreator;
use App\Services\LabelDeleter;
use App\Services\LabelUpdater;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LabelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
        $this->authorizeResource(Label::class, 'label');
    }

    public function index(LabelRepository $labelRepository): View
    {
        $labels = $labelRepository->getPaginated();

        return view('labels.index', compact('labels'));
    }

    public function create(): View
    {
        return view('labels.create');
    }

    public function store(
        StoreLabelRequest $storeLabelRequest,
        LabelCreator $labelCreator
    ): RedirectResponse {
        $labelCreator->create($storeLabelRequest->validated());

        flash(__('labels.flash.created'))->success();
        return redirect()->route('labels.index');
    }

    public function edit(Label $label): View
    {
        return view('labels.edit', compact('label'));
    }

    public function update(
        UpdateLabelRequest $updateLabelRequest,
        Label $label,
        LabelUpdater $labelUpdater
    ): RedirectResponse {
        $labelUpdater->update($label, $updateLabelRequest->validated());

        flash(__('labels.flash.updated'))->success();
        return redirect()->route('labels.index');
    }

    public function destroy(
        Label $label,
        LabelDeleter $labelDeleter
    ): RedirectResponse {
        $labelDeleter->delete($label);

        flash(__('labels.flash.deleted'))->success();
        return redirect()->route('labels.index');
    }
}
