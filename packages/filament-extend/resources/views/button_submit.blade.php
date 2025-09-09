<button id="submitButton"
        style="--c-400: var(--primary-400); --c-500: var(--primary-500); --c-600: var(--primary-600); position: relative; overflow: hidden;"
        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition
        duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary
        fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600
        text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus-visible:ring-custom-500/50
        dark:focus-visible:ring-custom-400/50
        fi-ac-btn-action w-full"
        type="submit" wire:loading.attr="disabled">


    <svg fill="none" id="loadingButton" style="display: none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
         class="animate-spin fi-btn-icon h-5 w-5"
         wire:loading.delay.shortest wire:target="submit">
        <path clip-rule="evenodd"
              d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
              fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
        <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
    </svg>

    <span class="fi-btn-label">
        {{$label??''}}
    </span>

</button>

