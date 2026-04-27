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
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class LabelController extends Controller implements HasMiddleware
{
    public function index(LabelRepository $labelRepository): View
    {
        $labels = $labelRepository->getPaginated();

        return view('labels.index', compact('labels'));
    }

    public function create(): View
    {
        Gate::authorize('create', Label::class);

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
        Gate::authorize('update', $label);

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
        Gate::authorize('delete', $label);

        $labelDeleter->delete($label);

        flash(__('labels.flash.deleted'))->success();

        return redirect()->route('labels.index');
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index']),
        ];
    }
}
