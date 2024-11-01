function post_karma(comment_id, action_url, elem) {
	elem.innerHTML = "<span><svg t='1691142752046' class='icon' viewBox='0 0 1024 1024' version='1.1' xmlns='http://www.w3.org/2000/svg' p-id='2098' width='18' height='18' ><path d='M375.5105 766.646c-17.5455 0-32.7285-7.812-36.288-25.011l-49.1715-235.3995-32.382 55.9125c-6.6465 11.277-18.8055 19.3095-31.878 19.3095H46.7135a37.044 37.044 0 0 1 0-74.088h157.9095l70.4025-118.9755a37.044 37.044 0 1 1 68.1345 11.3085l29.7675 143.8605L440.81 166.949a36.54 36.54 0 0 1 36.477-29.8935h0.1575c17.9865 0 33.327 12.474 36.351 30.177l72.45 422.7615 23.31-59.0625c5.544-14.175 19.215-23.5935 34.4295-23.5935h328.671a37.044 37.044 0 0 1 0 74.088h-303.5025l-62.685 158.445A37.0125 37.0125 0 0 1 535.5305 732.5L476.405 387.071 411.956 740.69c-3.15 17.451-18.2385 25.956-35.973 25.956h-0.4725z' fill='#d81e06' p-id='2099'></path></svg></svg></span>";
	var origin_karma = elem.getAttribute("data-karma");
	var new_karma = Number(!parseInt(origin_karma));
	var formData = new FormData();
	formData.append('comment_id', comment_id);
	formData.append('comment_karma', new_karma);

	var onDataReceived = function(data) {
		if (data.code == 200) {
			elem.setAttribute("data-karma", new_karma);
		} else {
			alert('Setting failed');
		}
	};

	var onDataError = function (reason) {
		alert('Setting failed(reason: \'' + reason + '\')，Please try again later.');
	};

	var onDataCompleted = function() {
		if (elem.getAttribute("data-karma") == '0') {
			elem.innerHTML = "<span title='Join the Touching Comments'><svg t='1691142362631' class='icon' viewBox='0 0 1024 1024' version='1.1' xmlns='http://www.w3.org/2000/svg' p-id='3461' width='18' height='18' ><path d='M709.56577067 110.4732032c-96.8271424 0-166.18710933 87.25008853-196.0785664 133.39655893-29.9242016-46.1464704-99.2525152-133.39655893-196.07747414-133.39655893-138.9415136 0-251.94071467 125.20683413-251.94071466 279.09134827 0 71.95780053 48.8076128 175.11579733 108.0556768 229.1037952 81.95836693 105.30066347 312.36872 294.85954133 340.81281173 294.85954133 28.94728533 0 254.41302293-185.87497493 337.85259093-293.59773653 60.28719787-54.93435093 109.33167147-158.2342464 109.33167147-230.3656C961.52176747 235.6789472 848.50401067 110.4732032 709.56577067 110.4732032M902.11434027 389.56455147c0 57.54855787-41.73561173 143.42877973-91.125008 187.5253632-1.35349333 1.2301504-2.58255147 2.58364373-3.81161067 4.06593706-73.42262933 95.66248427-221.2448032 214.31688427-292.6830368 266.2877408C461.38864 808.5743296 301.43851307 687.4618112 219.3229664 580.77818347c-1.1024416-1.44954773-2.39371733-2.80522347-3.74721067-4.06593707-49.2027456-44.03436693-90.71568533-129.69410027-90.71568533-187.14769493 0-121.14308053 86.3670432-219.71666667 192.5496608-219.71666667 68.4452672 0 134.3407296 74.08409387 169.27394667 147.5383776 4.6291648 9.7331424 14.8982464 15.7954816 26.80461866 15.7954816s22.17436267-6.0634304 26.83518187-15.7954816c34.90156373-73.45428373 100.76427947-147.5383776 169.24338453-147.5383776C815.7451136 169.8478848 902.11434027 268.42147093 902.11434027 389.56455147' fill='#d81e06' p-id='3462'></path></svg></span>";
		} else {
			elem.innerHTML = "<span title='Cancel the Touching Comments'><svg t='1691141971354' class='icon' viewBox='0 0 1024 1024' version='1.1' xmlns='http://www.w3.org/2000/svg' p-id='3103' width='18' height='18' ><path d='M709.56577067 110.4732032c-96.8271424 0-166.18710933 87.25008853-196.0785664 133.39655893-29.9242016-46.1464704-99.2525152-133.39655893-196.07747414-133.39655893-138.9415136 0-251.94071467 125.20683413-251.94071466 279.09134827 0 71.95780053 48.8076128 175.11579733 108.0556768 229.1037952 81.95836693 105.30066347 312.36872 294.85954133 340.81281173 294.85954133 28.94728533 0 254.41302293-185.87497493 337.85259093-293.59773653 60.28719787-54.93435093 109.33167147-158.2342464 109.33167147-230.3656C961.52176747 235.6789472 848.50401067 110.4732032 709.56577067 110.4732032' fill='#d81e06' p-id='3104'></path></svg></span>";
		}
	};

	if (typeof window.fetch !== "undefined") {
		fetch(action_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
				'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams(formData).toString()
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            onDataReceived(data);
        })
        .catch(error => {
           onDataError(error.message);
        })
        .finally(() => {
           onDataCompleted();
        });
	} else if (typeof jQuery !== "undefined") {
		jQuery.ajax({
			type: 'POST',
			url: action_url,
			data: new URLSearchParams(formData).toString(),
			dataType: 'json',
			timeout: 10000
		}).done(function (data) {
			onDataReceived(data);
		}).fail(function (jqXHR, textStatus, errorThrown) {
			onDataError(textStatus);
		}).always(function (jqXHR, textStatus) {
			onDataCompleted();
		});
	} else if (typeof XMLHttpRequest !== "undefined" ){
		var xhr = new XMLHttpRequest();
        xhr.open('POST', action_url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.timeout = 10000;

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                var data = JSON.parse(xhr.responseText);
                onDataReceived(data);
            } else {
                onDataError(xhr.statusText);
            }
        };

        xhr.onerror = function () {
			onDataError(xhr.statusText);
        };

        xhr.onloadend = function () {
           onDataCompleted();
        };

        xhr.send(new URLSearchParams(formData).toString());
	} else {
		alert("JavaScript cannot make HTTP request");
	}
	
	return false;
}