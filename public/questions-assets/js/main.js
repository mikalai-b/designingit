(async function() {
	const questions = document.querySelector('.questions');
	const questionsFooter = document.querySelector('.questions-footer');
	const allSteps = document.querySelectorAll('.questions__step');
	const nextBtns = document.querySelectorAll('[data-next]');
	const prevBtn = document.querySelector('[data-prev]');
	const progressWrapper = document.querySelector('.questions__progress');
	const progressBar = document.querySelector('.questions__progress-bar');
	const questionsTypeLocal = (typeof questionsType !== 'undefined') && questionsType === 'weight' ? 'weight' : 'mental_health';
	const localStorageVarName = questionsTypeLocal === 'weight' ? 'questionsWeightFormData' : 'questionsFormData';
	const localStorageProductId = questionsTypeLocal === 'weight' ? 'weightProductId' : 'productId';


	const weightLossPercentage = 20; // how many percent to lose weight
	const weightLossMonths = 5; // how many months to lose weight
	const bmiWeightLimit = 27; // to show message that weight is too low
	const bmiContinueLimit = 27; // to disable next button if bmi is too low

	let products = [];

	let formData = {
		completedSteps: [],
		activeStep: null,
		data: {},
	};

	// copy step note if needed
	const copyStepNote = (step) => {
			// check if question has note, copy note to note outside of question
			const note = step.querySelector('.questions__step--note');
			const noteOutput = document.querySelector('.questions__step--note.--outside');
			if (note) {
				noteOutput.innerHTML = note.innerHTML;
			} else {
				noteOutput.innerHTML = '';
			}
	};

	// set products to html
	const setProducts = (products) => {
		const productsSelect = document.querySelector('[data-products-select]');
		if (productsSelect) {
			products.forEach((product) => {
				const productNote = product.strength && product.quantity ? `(${product.strength}, ${product.quantity})` : '';
				const option = document.createElement('option');
				option.value = product.id;
				option.textContent = `${product.name} ${productNote}`;
				productsSelect.appendChild(option);
			});
		}
	}

	// set product to html
	const setProduct = (product) => {
		const productNotExists = document.querySelectorAll('[data-product-not-exist]');
		const productOutput = document.querySelectorAll('[data-product-name]');
		const productImage = document.querySelectorAll('[data-product-image]');
		const productOption = document.querySelectorAll('[data-product-option]');

		if (product) {
			productOutput.forEach(item => item.textContent = product.name);
			productNotExists.forEach(item => item.style.display = 'none');
			if (product.thumbnail) {
				productImage.forEach(item => {
					item.src = product.thumbnail;
					item.alt = product.name;
				});
			}
			productOption.forEach(item => item.textContent = item.dataset.productOption + ' ' + product.name);
		} else {
			productOutput.forEach(item => item.textContent = 'medication');
			productNotExists.forEach(item => item.style.display = 'block');
			productOption.forEach(item => item.textContent = item.dataset.productOptionDefault);
		}
	}

	// get products from api
	const getProducts = async () => {
		const response = await fetch('/api/v1/products');
		if (response.ok) {
			let res = await response.json().then((data) => data.data);
			products = res.filter(p => p.category && (p.category.title).toLowerCase() === 'weight lost');
			setProducts(products);
		}
	};
	await getProducts();

	// submit form
	const submitForm = (e) => {
		const productsSelect = document.querySelector('[data-products-select]');
		// check if product exist in local storage
		const productId = localStorage.getItem(localStorageProductId);
		const optionsSelect = document.querySelector('[name="all_set"]');
		const newUser = document.querySelector('[name="new_user"]:checked');
		const newUserString = '&newuser=' + (newUser ? newUser.value : 'no');

		let selectedProduct;

		if (productId) {
			if (questionsTypeLocal === 'weight') {
				selectedProduct = productId;
			} else {
				if (optionsSelect.value === 'no') {
					selectedProduct = productId;
				} else {
					selectedProduct = productsSelect.value;
				}
			}
		} else {
			if (optionsSelect?.value === 'no') {
				selectedProduct = null;
			} else {
				selectedProduct = productsSelect?.value ? productsSelect.value : null;
			}
		}

		// if we have product, go to cart with this product
		if (selectedProduct) {
			window.location.href = `/cart/products/${selectedProduct}?form=${questionsTypeLocal}${newUserString}`;
		} else {
			alert('Please select a product');
		}
	}

	// make questions-footer fixed if we scroll above bottom of questions
	const checkQuestionsFooterPosition = () => {
		// check if bottom of the view is above bottom of questions
		const questionsBottom = questions.getBoundingClientRect().bottom;
		const viewBottom = window.innerHeight;
		if (questionsBottom > viewBottom) {
			questionsFooter.classList.add('fixed');
		} else {
			questionsFooter.classList.remove('fixed');
		}
	};
	checkQuestionsFooterPosition();
	'resize scroll'.split(' ').forEach(function(e) {
		window.addEventListener(e, checkQuestionsFooterPosition);
	});

	// build progress bar
	const buildProgressBar = () => {
		// count all groups
		const groups = Array.from(allSteps).map(step => step.dataset.progress ? (step.dataset.progress).split('/')[1] : '1');
		const countGroups = Array.from(new Set(groups)).length;
		for (let i = 1; i <= countGroups; i++) {
			const progressItem = document.createElement('div');
			progressItem.classList.add('questions__progress-item');
			progressBar.appendChild(progressItem);
		}
	};
	buildProgressBar();

	// check if we need to show progress
	const progressItems = document.querySelectorAll('.questions__progress-item');
	const checkIfNeedProgress = () => {
		const activeStep = document.querySelector('.questions__step.active');
		if (activeStep && activeStep.dataset.progress) {
			progressWrapper.classList.add('active');
			const stepGroup = activeStep.dataset.progress ? Number((activeStep.dataset.progress).split('/')[1]) : 1;
			const activeProgressItem = progressItems[stepGroup - 1];

			// remove active class from all progress items
			Array.from(progressItems).forEach((progressItem) => {
				progressItem.classList.remove('active');
				progressItem.style.removeProperty('--progress');
			});

			// set active progress item
			activeProgressItem.classList.add('active');

			// calculate progress bar width for this group
			const groupSteps = Array.from(allSteps).filter(step => step.dataset.progress ? Number((step.dataset.progress).split('/')[1]) === stepGroup : false);
			const groupStepsCount = groupSteps.length;
			const activeStepIndex = Array.from(groupSteps).indexOf(activeStep);
			const progressWidth = (activeStepIndex + 1) / groupStepsCount * 100;

			activeProgressItem.style.setProperty('--progress', -1 * (100 - progressWidth) + '%');
		} else {
			progressWrapper.classList.remove('active');
		}
	};
	checkIfNeedProgress();

	// set url
	const setUrl = (stepName) => {
		// chance url and save history state
		const url = new URL(window.location);
		url.searchParams.set('step', stepName);
		window.history.pushState({ step: stepName }, null, url);
	};
	setUrl(formData.activeStep);

	// get diagnosis based on score
	const getDiagnosis = (score) => {
		let diagnosis;
		if (score >= 0 && score <= 9) {
			diagnosis = 'mild';
		} else if (score >= 10 && score <= 14) {
			diagnosis = 'moderate';
		} else if (score >= 15) {
			diagnosis = 'severe';
		} else {
			diagnosis = 'none';
		}
		return diagnosis;
	};

	// calculate and set gad scores
	const gadScores = document.querySelectorAll('[data-gad] [data-score]');
	const calculateGad = () => {
		let gadScore = 0;
		Array.from(gadScores).forEach((gadScoreItem) => {
			if (gadScoreItem.checked) {
				gadScore += Number(gadScoreItem.dataset.score);
			}
		});
		const gadScoreOutput = document.querySelectorAll('[data-gad-result]');
		gadScoreOutput.forEach(item => item.textContent = gadScore);

		const gadDiagnosisOutput = document.querySelectorAll('[data-gad-result-name]');
		gadDiagnosisOutput.forEach(item => item.textContent = getDiagnosis(gadScore));
	};

	// calculate and set phq scores
	const phqScores = document.querySelectorAll('[data-phq] [data-score]');
	const calculatePhq = () => {
		let phqScore = 0;
		Array.from(phqScores).forEach((phqScoreItem) => {
			if (phqScoreItem.checked) {
				phqScore += Number(phqScoreItem.dataset.score);
			}
		});
		const phqScoreOutput = document.querySelectorAll('[data-phq-result]');
		phqScoreOutput.forEach(item => item.textContent = phqScore);

		const phqDiagnosisOutput = document.querySelectorAll('[data-phq-result-name]');
		phqDiagnosisOutput.forEach(item => item.textContent = getDiagnosis(phqScore));
	};

	// if data-animation-step attribute is set, we need to go to next step after animation. also need to hide next button
	const checkAnimationStep = (stepIndex, stepToActive) => {
		let timeOut = null;
		let timoutIndex = null;
		if (stepToActive.hasAttribute('data-animation-step')) {
			timoutIndex = stepIndex;
			const animationTime = parseInt(getComputedStyle(stepToActive).getPropertyValue('--animation-time')) || 2000;
			nextBtns.forEach((nextBtn) => { nextBtn.style.display = 'none'; });
			timeOut = setTimeout(() => {
				goToStep(stepIndex + 1, stepToActive);
				nextBtns.forEach((nextBtn) => { nextBtn.style.display = 'block'; });
			}, animationTime + 1500);
		} else {
			nextBtns.forEach((nextBtn) => { nextBtn.style.display = 'block'; });
		}
		return { timeOut, timoutIndex };
	};

	// check if we need to show product in sidebar
	const checkIfNeedProduct = (step) => {
		const productExists = document.querySelectorAll('[data-product-exist]');
		if (step.hasAttribute('data-show-product') && step.dataset.showProduct === 'false') {
			productExists.forEach(item => {
				item.style.display = 'none';
				item.classList.remove('active');
			});
		} else {
			productExists.forEach(item => {
				item.style.display = 'grid';
				item.classList.add('active');
			});
		}
	}

	// indexes of steps, starting from 1
	const saveFormData = (currentStepIndex, nextStepIndex) => {
		// clear all arrays from formData.data to avoid duplicates
		formData.data = {};

		const inputs = document.querySelectorAll('input, select, textarea');
		inputs.forEach((input, index) => {
			let label = '';
			if (input.closest('.questions__step').querySelector('.questions__title')) {
				label = input.closest('.questions__step').querySelector('.questions__title').textContent;
			}

			// if radio or checkbox, we need to save only checked inputs
			if (input.type === 'radio') {
				if (input.checked) {
					formData.data[input.name] = {
						question: label,
						answer: input.value,
						display_order: index,
						key: input.name,
						type: 'radio'
					};
				}
			} else if (input.type === 'checkbox') {
				if (input.checked) {
					if (!formData.data[input.name]) {
						formData.data[input.name] = {
							question: label,
							answer: [],
							key: input.name,
							type: 'checkbox'
						};
					}
					formData.data[input.name].answer.push(input.value);
				}
			} else {
				if (input.value) {
					formData.data[input.name] = {
						question: label,
						answer: input.value,
						key: input.name,
						type: 'text',
					};
				}
			}
		});

		// push completed steps progress
		if (!formData.completedSteps.includes(currentStepIndex)) {
			const currentStepElement = allSteps[parseInt(currentStepIndex) - 1];
			if (typeof currentStepElement !== 'undefined' && currentStepElement.hasAttribute('data-animation-step')) {

			} else {
				formData.completedSteps.push(currentStepIndex);
			}
		}

		// also we need to now what step is active
		formData.activeStep = nextStepIndex;
		localStorage.setItem(localStorageVarName, JSON.stringify(formData));
	};

	// show elements based on steps
	const checkShowOnSteps = (stepIndex) => {
		const showOnSteps = document.querySelectorAll('[data-show-on-steps]');
		showOnSteps.forEach((showOnStep) => {
			const steps = showOnStep.dataset.showOnSteps.split(',').map(step => step.trim());
			if (steps.includes((stepIndex + 1).toString())) {
				showOnStep.style.display = 'block';
			} else {
				showOnStep.style.display = 'none';
			}
		});
	}

	// hide elements based on steps
	const checkHideOnSteps = (stepIndex) => {
		const hideOnSteps = document.querySelectorAll('[data-hide-on-steps]');
		hideOnSteps.forEach((hideOnStep) => {
			const steps = hideOnStep.dataset.hideOnSteps.split(',').map(step => step.trim());
			if (steps.includes((stepIndex + 1).toString())) {
				hideOnStep.style.display = 'none';
			} else {
				hideOnStep.style.display = 'block';
			}
		});
	}

	// check if need to calculate BMI, and calculate it if needed
	const bmiInputs = document.querySelectorAll('[data-bmi-input]');
	const bmiStep = document.querySelectorAll('[data-bmi-calculate]');
	const bmiScoreInput = document.querySelector('[data-bmi-score]');
	const bmiCalculate = () => {
		const bmiWeightLimitElement = document.querySelector('[data-bmi-weight-limit]');
		const feet = document.querySelector('[data-bmi-input="feet"]') ? parseInt(document.querySelector('[data-bmi-input="feet"]').value) || 0 : 0;
		const inches = document.querySelector('[data-bmi-input="inches"]') ? parseInt(document.querySelector('[data-bmi-input="inches"]').value) || 0 : 0;
		const pounds = document.querySelector('[data-bmi-input="pounds"]') ? parseInt(document.querySelector('[data-bmi-input="pounds"]').value) || 0 : 0;
		const fullInches = feet * 12 + inches;
		const bmiScoreFormula = (pounds * 703 / fullInches) / fullInches || 0;
		const bmiScore = Math.round(bmiScoreFormula * 10) / 10;
		const bmiOutput = document.querySelectorAll('[data-bmi-output]');
		if (bmiOutput.length === 0) return;
		bmiOutput.forEach(item => { item.textContent = bmiScore; });
		bmiScoreInput.value = bmiScore;

		if (bmiScore < bmiWeightLimit) {
			bmiWeightLimitElement.style.display = 'block';
		} else {
			bmiWeightLimitElement.style.display = 'none';
		}

		const currentStep = document.querySelector('.questions__step.active');
		if (bmiScore < bmiContinueLimit && bmiStep[0] === currentStep) {
			nextBtns.forEach((nextBtn) => { nextBtn.disabled = true; });
		} else {
			nextBtns.forEach((nextBtn) => { nextBtn.disabled = false; });
		}

	}
	bmiInputs.forEach((bmiInput) => {
		bmiInput.addEventListener('input', () => {
			bmiCalculate();
		});
	});

	// check which image we need to show on circle-animation step
	const checkCircleAnimationImage = (stepIndex) => {
		const currentStepEl = allSteps[stepIndex];
		const images = currentStepEl.querySelectorAll('[data-product-condition]');
		const productId = localStorage.getItem(localStorageProductId);
		if (images.length === 0) return;



		let imageToActive = Array.from(images).find((image) => image.dataset.productCondition === 'default');

		images.forEach((image) => {
			image.style.display = 'none';
			const imageProductId = image.dataset.productCondition;
			if (imageProductId === productId) {
				imageToActive = image;
			}
		});

		imageToActive.style.display = 'block';
	}

	// check product condition in product
	const productCondition = document.querySelector('.questions-product');
	if (productCondition)  {
		const productId = localStorage.getItem(localStorageProductId);
		const productConditionElms = productCondition.querySelectorAll('[data-product-condition]');
		productConditionElms.forEach((pc) => {
			pc.style.display = 'none';
			const pcId = pc.dataset.productCondition;
			if (pcId === productId) {
				pc.style.display = 'block';
			}
		});

	}

	// set active step by index
	const setStepActive = (stepIndex, changeUrl = true) => {
		const activeStep = document.querySelector('.questions__step.active');
		const stepToActive = allSteps[stepIndex];
		if (stepToActive && stepToActive !== activeStep) {
			stepToActive.classList.add('active');
			if (activeStep) {
				activeStep.classList.remove('active');
			}
			// scroll to top smoothly
			window.scrollTo({
				top: 0,
				behavior: 'smooth',
			});
			if (changeUrl) setUrl(stepToActive.dataset.step);

			checkCircleAnimationImage(stepIndex);
			checkIfNeedProduct(stepToActive);
			copyStepNote(stepToActive);
			checkIfNeedProgress();
			checkQuestionsFooterPosition();
			calculateGad();
			calculatePhq();
			checkAnimationStep(stepIndex, stepToActive);
			checkShowOnSteps(stepIndex);
			checkHideOnSteps(stepIndex);
			bmiCalculate(stepIndex);
		}
	};

	// go to step by index
	const goToStep = (stepIndex, currentStep) => {
		let stepIndexToActive = stepIndex;
		const activeStep = document.querySelector('.questions__step.active');
		// check if input, select or textarea has data-additional attribute, if yes - go to additional step
		const inputs = activeStep.querySelectorAll('input, select, textarea');
		let additionalStepName = null;
		Array.from(inputs).forEach((input) => {
			// if it's select, we need to check if selected option has data-additional attribute
			if (input.tagName === 'SELECT') {
				const selectedOption = input.querySelector('option:checked');
				if (selectedOption.dataset.additional) {
					additionalStepName = selectedOption.dataset.additional;
				}
			}

			// if it's input radio or checkbox, we need to check if it has data-additional attribute
			if (input.checked && input.dataset.additional) {
				additionalStepName = input.dataset.additional;
			}

			// if it's textarea, we need to check if it has data-additional attribute
			if (input.tagName === 'TEXTAREA' && input.dataset.additional) {
				additionalStepName = input.dataset.additional;
			}
		});

		// if we have additional step name, we need to find it and go to it
		if (additionalStepName) {
			const additionalStep = document.querySelector(`[data-step="${additionalStepName}"]`);
			if (additionalStep) {
				// set index of additional step to active
				stepIndexToActive = Array.from(allSteps).indexOf(additionalStep);
			}
		} else {
			// if we don't have additional step name, we need to go to next step without --additional class
			for (let i = stepIndex; i < allSteps.length; i++) {
				if (!allSteps[i].classList.contains('--additional')) {
					stepIndexToActive = i;
					break;
				}
			}
		}

		const stepName = allSteps[stepIndexToActive].dataset.step;

		saveFormData(currentStep.dataset.step, stepName);
		setStepActive(stepIndexToActive);
	};

	// validate step
	const validateStep = (step) => {
		const inputs = step.querySelectorAll('input, select, textarea');
		let isValid = true;
		let isRadioOrCheckbox = false;
		Array.from(inputs).forEach((input) => {
			// check if input is visible
			if (input.offsetParent === null) {
				return;
			}
			// if (input.required && !input.value) {
			// 	input.classList.add('invalid');
			// 	isValid = false;
			// } else {
			// 	input.classList.remove('invalid');
			// }

			// if radio or checkbox, check that at least one input is checked
			if (input.type === 'radio' || input.type === 'checkbox') {
				const radioGroup = step.querySelectorAll(`[name="${input.name}"]`);
				let isRadioGroupValid = false;
				Array.from(radioGroup).forEach((radio) => {
					if (radio.checked) {
						isRadioGroupValid = true;
					}
				});
				if (!isRadioGroupValid) {
					input.classList.add('invalid');
					isValid = false;
					isRadioOrCheckbox = true;
				} else {
					input.classList.remove('invalid');
				}
			} else {
				if (!input.value) {
					input.classList.add('invalid');
					isValid = false;
				} else {
					input.classList.remove('invalid');
				}
			}
		});

		// if (isRadioOrCheckbox) {
		// 	alert('Please select an option');
		// }

		return isValid;
	};

	const nextStep = (nextBtn = null) => {
			const currentStep = document.querySelector('.questions__step.active');
			const currentStepIndex = Array.from(allSteps).indexOf(currentStep);
			const needValidation = (!(nextBtn && nextBtn.dataset.validation && nextBtn.dataset.validation === 'false'));

			if (needValidation) {
				if (!validateStep(currentStep)) {
					return;
				}
			}

			// if we have data-clear-on-click attribute, we need to clear inputs
			if (nextBtn && nextBtn.dataset.clearOnClick) {
				const inputs = currentStep.querySelectorAll(nextBtn.dataset.clearOnClick);
				Array.from(inputs).forEach((input) => {
					if (input.type === 'radio' || input.type === 'checkbox') {
						input.checked = false;
					} else {
						input.value = '';
					}
				});
			}

			// if last step, we need to submit form
			if (currentStepIndex === allSteps.length - 1) {
				// submit form
				submitForm();
			} else {
				goToStep(currentStepIndex + 1, currentStep);
			}
	}

	// go to next step
	Array.from(nextBtns).forEach((nextBtn, index) => {
		nextBtn.addEventListener('click', () => {
			nextStep(nextBtn);
		});
	});

	// go to prev step
	prevBtn.addEventListener('click', () => {
		window.history.back();
	});

	const nextOnChange = document.querySelectorAll('[data-next-on-change]');
	Array.from(nextOnChange).forEach((nextOnChangeItem) => {
		nextOnChangeItem.addEventListener('change', () => {
			nextStep();
		});
	});

	// set active step by history state
	window.onpopstate = function(event) {
		// get current step name
		const currentStepName = document.querySelector('.questions__step.active').dataset.step;

		// get previous step name from formDate.completedSteps
		const currentStepIndex = formData.completedSteps.indexOf(currentStepName);
		const prevStepName = formData.completedSteps[currentStepIndex - 1];

		// if we have previous step, we need to go to it
		if (prevStepName) {
			const prevStepIndex = Array.from(allSteps).findIndex((step) => step.dataset.step === prevStepName);
			setStepActive(prevStepIndex);
		} else {
			// if we don't have previous step, we need to last step in completed steps
			const lastStepName = formData.completedSteps[formData.completedSteps.length - 1];
			const lastStepIndex = Array.from(allSteps).findIndex((step) => step.dataset.step === lastStepName);
			setStepActive(lastStepIndex);
		}
	};

	// tabs
	const tabs = document.querySelectorAll('.questions__tabs');
	Array.from(tabs).forEach((tab) => {
		const tabBtns = tab.querySelectorAll('.questions__tabs--top-item');
		const tabContents = tab.querySelectorAll('.questions__tabs--content-item');
		Array.from(tabBtns).forEach((tabBtn, index) => {
			tabBtn.addEventListener('click', () => {
				Array.from(tabBtns).forEach((tabBtn) => {
					tabBtn.classList.remove('active');
				});
				tabBtn.classList.add('active');
				Array.from(tabContents).forEach((tabContent) => {
					tabContent.classList.remove('active');
				});
				tabContents[index].classList.add('active');
			});
		});
	});

	// conditional fields
	const conditionalFields = document.querySelectorAll('[data-conditional-field]');
	Array.from(conditionalFields).forEach((conditionalField) => {
		const wrapper = conditionalField.closest('.questions__step');
		const fieldContent = wrapper.querySelectorAll(`[data-conditional-content="${conditionalField.dataset.conditionalField}"]`);

		// if is radio - we need to also listen to uncheck event
		if (conditionalField.type === 'radio') {
			const radioGroup = wrapper.querySelectorAll(`[name="${conditionalField.name}"]`);
			let prevCheckedRadio = null;

			const toggleConditionalField = (radio) => {
				if (prevCheckedRadio) {
					const hideField = document.querySelector(`[data-conditional-content="${prevCheckedRadio.dataset.conditionalField}"]`);
					if (hideField) {
						hideField.classList.remove('active');
					}

					const showField = document.querySelector(`[data-conditional-content="${radio.dataset.conditionalField}"]`);
					if (showField) {
						showField.classList.add('active');
					}
				}

				if (radio.checked) {
					if (prevCheckedRadio) {
						prevCheckedRadio.checked = false;
					}
					prevCheckedRadio = radio;
				}
			};

			Array.from(radioGroup).forEach((radio) => {
				radio.addEventListener('change', () => {
					toggleConditionalField(radio);
				});
				toggleConditionalField(radio);
			});
		}

		// if is option - we need to listen to change event
		if (conditionalField.tagName === 'OPTION') {
			const parentSelect = conditionalField.closest('select');
			const toggleConditionalField = (firstLoad = true) => {
				if (parentSelect.value === conditionalField.value) {
					fieldContent.forEach(function(item) {
						item.classList.add('active');
					});
				} else {
					fieldContent.forEach(function(item) {
						if (!firstLoad) {
							item.classList.remove('active');
						}
					});
				}
			}
			toggleConditionalField();
			parentSelect.addEventListener('change', () => {
				toggleConditionalField(false);
			});

			return;
		}

		// show conditional field if we have checked input
		if (conditionalField.checked) {
			fieldContent.forEach(function(item) {
				item.classList.add('active');
			});
		}
		conditionalField.addEventListener('change', () => {
			if (conditionalField.checked) {
				fieldContent.forEach(function(item) {
					item.classList.add('active');
				});
			} else {
				fieldContent.forEach(function(item) {
					item.classList.remove('active');
				});
			}
		});
	});

	// animating elements of questions__step--weight-chart
	const weightChart = document.querySelector('.questions__step--weight-chart');
	if (weightChart) {
		const styles = window.getComputedStyle(weightChart);
		const animationTime = parseInt(styles.getPropertyValue('--animation-time')) || 2000;
		const currentWeight = document.querySelector('.questions__weight-current-weight span:first-of-type');
		const weightLoss = document.querySelector('.questions__weight-future-weight span:first-of-type');
		const dateWeight = document.querySelector('.questions__weight-date');

		let formatter = new Intl.DateTimeFormat('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
		let formattedDate = formatter.format(new Date());

		// observe active class
		const observer = new MutationObserver((mutations) => {
			mutations.forEach((mutation) => {
				if (mutation.attributeName === 'class') {
					const isActive = mutation.target.classList.contains('active');
					if (isActive) {
						dateWeight.textContent = formattedDate;

						// animate date
						let dateAnimationCount = 1;
						const dateAnimationInterval = setInterval(() => {
							let date = new Date();
							date.setMonth(date.getMonth() + dateAnimationCount);
							dateWeight.textContent = formatter.format(date);
							dateAnimationCount++;
							if (dateAnimationCount > weightLossMonths) {
								clearInterval(dateAnimationInterval);
							}
						}, animationTime / weightLossMonths);

						// animate weight
						let weightAnimationCount = 1;
						let weightLossAnimationCount = weightLossMonths * 2;
						const current = parseInt(weightInput.value);
						const finalWeight = current - current * (weightLossPercentage / 100);
						const finalWeightLoss = current - finalWeight;
						const weightAnimationInterval = setInterval(() => {
							currentWeight.textContent = parseInt(current - (finalWeightLoss / weightLossAnimationCount) * weightAnimationCount);
							weightLoss.textContent = parseInt((finalWeightLoss / weightLossAnimationCount) * weightAnimationCount);
							weightAnimationCount++;
							if (weightAnimationCount > weightLossAnimationCount) {
								clearInterval(weightAnimationInterval);
							}
						}, animationTime / weightLossAnimationCount);
					}
				}
			});
		});

		observer.observe(weightChart, {
			attributes: true,
		});
	}

		// load form data from local storage
	const loadFormDataFromStorage = () => {
		const storageFormData = JSON.parse(localStorage.getItem(localStorageVarName));
		let stepToActiveIndex;
		if (storageFormData) {
			formData = storageFormData;

			// set active step
			stepToActiveIndex = formData.activeStep;

			// fill form from storage data
			Object.entries(formData.data).forEach(([key, value]) => {
				const input = document.querySelector(`[name="${key}"]`);
				if (input) {
					if (input.type === 'radio') {
						const radio = document.querySelector(`[name="${key}"][value="${value.answer}"]`);
						if (radio) {
							radio.checked = true;
						}
					} else if (input.type === 'checkbox') {
						if (Array.isArray(value.answer)) {
							value.answer.forEach((item) => {
								const checkbox = document.querySelector(`[name="${key}"][value="${item}"]`);
								if (checkbox) {
									checkbox.checked = true;
								}
							});
						}
					} else {
						input.value = value.answer;
					}
				}
			});

			// push to history state all completed steps
			formData.completedSteps.forEach((step) => {
				const url = new URL(window.location);
				url.searchParams.set('step', step);
				window.history.pushState({ step: step }, null, url);
			});
		} else {
			stepToActiveIndex = allSteps[0].dataset.step;
		}

		formData.activeStep = stepToActiveIndex;
		setStepActive(stepToActiveIndex - 1);
		// const stepToActive = document.querySelector(`[data-step="${stepToActiveIndex}"]`);
		// copyStepNote(stepToActive);
		// stepToActive.classList.add('active');

		// load product from storage
		const productId = localStorage.getItem(localStorageProductId);
		let product = null;

		if (productId) {
			product = products.find((product) => product.id === productId);
		}
		setProduct(product);
	};
	loadFormDataFromStorage();

	// duplicate name input to all name outputs
	const nameInput = document.querySelector('.questions__input-text-name input');
	const nameOutputs = document.querySelectorAll('.questions__person-name');
	const setNameOutput = () => {
		Array.from(nameOutputs).forEach((nameOutput) => {
			nameOutput.textContent = nameInput.value ? nameInput.value + '!' : '';
		});
	};

	if (nameInput) {
		setNameOutput();
		nameInput.addEventListener('input', () => {
			setNameOutput();
		});
	}

	// duplicate state select to all state outputs
	const stateSelect = document.querySelector('.questions__input-select-state select');
	const stateOutputs = document.querySelectorAll('.questions__person-state');
	const setStateOutput = () => {
		Array.from(stateOutputs).forEach((stateOutput) => {
			stateOutput.textContent = stateSelect.value;
		});
	};

	if (stateSelect) {
		setStateOutput();
		stateSelect.addEventListener('change', () => {
			setStateOutput();
		});
	}

	// duplicate weight input to all weight outputs
	const weightInput = document.querySelector('[data-weight-input]');
	const weightOutputs = document.querySelectorAll('[data-weight-output]');
	const weightOutputsFuture = document.querySelectorAll('[data-weight-output-future]');
	const setWeightOutput = () => {
		Array.from(weightOutputs).forEach((weightOutput) => {
			weightOutput.textContent = weightInput.value ? weightInput.value : '';
		});
		Array.from(weightOutputsFuture).forEach((weightOutput) => {
			weightOutput.textContent = weightInput.value ? Math.round(parseInt(weightInput.value) * (weightLossPercentage / 100)) : '';
		});
	};

	if (weightInput) {
		setWeightOutput();
		weightInput.addEventListener('input', () => {
			setWeightOutput();
		});
	}

})();