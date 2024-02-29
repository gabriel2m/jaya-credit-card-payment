import InputError from '@/Components/InputError';
import SelectInput from '@/Components/SelectInput';
import TextInput from '@/Components/TextInput';
import SuccessButton from '@/Components/SuccessButton';
import { getInstallments, getPaymentMethods } from '@mercadopago/sdk-react';
import { createCardToken } from '@mercadopago/sdk-react/coreMethods';
import { useState } from 'react';
import { toast } from 'react-toastify';

export default function PaymentForm() {
    const inputValue = (name, unmask = true) => {
        const input = document.querySelector(`[name="${name}"]`);

        if (input.value === '') {
            return null;
        }

        if (unmask && input.inputmask) {
            return input.inputmask.unmaskedvalue();
        }

        return input.value;
    };

    const [disabled, setDisabled] = useState(false);

    const [errors, setErrors] = useState({});

    const setError = (input, message) => setErrors(errors => {
        if (!document.querySelectorAll(`[name="${input}"]`).length) {
            return errors;
        }

        errors = {
            ...errors,
            [input]: message
        };

        setDisabled(
            Object.values(errors).reduce((disabled, message) => disabled || message != null, false)
        );

        return errors;
    });

    const removeError = (input) => setError(input, null);

    const [installmentOptions, setInstallmentOptions] = useState([
        {
            value: null,
            label: 'Installments',
        }
    ]);

    const getBin = () => {
        const credit_card_number = inputValue('credit_card_number');

        if (!credit_card_number || credit_card_number.length < 8) {
            return null;
        }

        return credit_card_number.slice(0, 8);
    };

    const updateInstallments = async () => {
        const transaction_amount = inputValue('transaction_amount');

        if (transaction_amount === null) {
            return;
        }

        if (transaction_amount < 1) {
            return setError('transaction_amount', 'Invalid amount');
        }

        const bin = getBin();

        if (!bin) {
            return;
        }

        let installments = [];

        try {
            installments = await getInstallments({
                locale: document.documentElement.lang,
                amount: `${transaction_amount}`,
                bin: bin,
            });
        } catch (error) {
            return setError('credit_card_number', 'Invalid credit card number');
        }

        if (!installments.length) {
            return setError('transaction_amount', 'Invalid amount');
        }

        installments = installments[0];

        if (installments.payment_type_id != "credit_card") {
            return setError('credit_card_number', 'Invalid credit card number');
        }

        setInstallmentOptions(
            installments.payer_costs.map(option => ({
                value: option.installments,
                label: option.recommended_message
            }))
        );

        removeError('installments');
    }

    const failAlert = (message = "Something went wrong.") => {
        toast.error(<span className="font-bold">{message}</span>);
    };

    const successAlert = (message = "All successful.") => {
        toast.success(<span className="font-bold">{message}</span>);
    };

    const submit = async (form) => {
        const bin = getBin();

        if (!bin) {
            return setError('credit_card_number', 'Invalid credit card number');
        }

        let paymentMethods;

        try {
            paymentMethods = await getPaymentMethods({ bin: bin });
        } catch (error) {
            return setError('credit_card_number', 'Invalid credit card number');
        }

        if (!paymentMethods.results.length) {
            return setError('credit_card_number', 'Invalid credit card number');
        }

        let token;

        try {
            token = await createCardToken({
                cardNumber: inputValue('credit_card_number'),
                cardholderName: inputValue('holder_name'),
                cardExpirationMonth: inputValue('expiration_month', false),
                cardExpirationYear: inputValue('expiration_year', false),
                securityCode: inputValue('security_code'),
                identificationType: inputValue('payer.identification.type'),
                identificationNumber: inputValue('payer.identification.number'),
            });
        } catch (error) {
            if (error.status != 400) {
                return failAlert();
            }

            return failAlert('Invalid payment details.');
        }

        try {
            const response = await axios.post(route('payments.store'), {
                transaction_amount: inputValue('transaction_amount'),
                installments: inputValue('installments'),
                payment_method_id: paymentMethods.results[0].id,
                token: token.id,
                payer: {
                    email: inputValue('payer.email'),
                    identification: {
                        type: inputValue('payer.identification.type'),
                        number: inputValue('payer.identification.number'),
                    }
                }
            });

            if (response.status != 201) {
                return failAlert();
            }

            successAlert('Successful payment.');

            form.remove();
        } catch (error) {
            if (error.response.status != 422 || !error.response.data.errors) {
                return failAlert();
            }

            for (const [input, message] of Object.entries(
                error.response.data.errors
            )) {
                setError(input, message);
            }
        }
    };

    const [processing, setProcessing] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setProcessing(true);
        await submit(e.target);
        setProcessing(false);
    }

    return (
        <form onSubmit={handleSubmit}>
            <div className="grid md:grid-cols-2 lg:gap-16 gap-8">
                <div className="shadow-lg px-4 rounded-md flex flex-col gap-5 py-6">
                    <h3 className="text-xl font-bold">
                        Payer Details
                    </h3>

                    <div>
                        <TextInput
                            type="email"
                            name="payer.email"
                            placeholder="Email"
                            className="block w-full"
                            onChange={() => removeError('payer.email')}
                            required
                        />

                        <InputError message={errors['payer.email']} className="mt-2" />
                    </div>

                    <div>
                        <SelectInput
                            name="payer.identification.type"
                            options={[
                                {
                                    value: 'CPF',
                                    label: 'CPF',
                                    selected: true
                                }
                            ]}
                            className="block w-full"
                            onChange={() => removeError('payer.identification.type')}
                        />

                        <InputError message={errors['payer.identification.type']} className="mt-2" />
                    </div>

                    <div>
                        <TextInput
                            type="text"
                            name="payer.identification.number"
                            placeholder="Identification number"
                            className="block w-full"
                            mask={{
                                mask: '999.999.999-99'
                            }}
                            onChange={() => removeError('payer.identification.number')}
                            required
                        />

                        <InputError message={errors['payer.identification.number']} className="mt-2" />
                    </div>
                </div>
                <div className="shadow-lg px-4 rounded-md flex flex-col gap-5 py-6">
                    <h3 className="text-xl font-bold">
                        Payment Details
                    </h3>

                    <div>
                        <TextInput
                            type="text"
                            name="transaction_amount"
                            autoComplete="off"
                            mask={{
                                alias: "currency",
                                radixPoint: ",",
                                numericInput: true,
                                rightAlign: false,
                                onUnMask: (value) => Number(value.replace(/\D/g, "")) / 100
                            }}
                            placeholder="Transaction amount"
                            className="block w-full"
                            onChange={() => {
                                removeError('transaction_amount');

                                setTimeout(function () {
                                    updateInstallments();
                                }, 1000)
                            }}
                            required
                        />

                        <InputError message={errors.transaction_amount} className="mt-2" />
                    </div>

                    <div>
                        <TextInput
                            type="text"
                            name="credit_card_number"
                            mask={{
                                mask: '9999 9999 9999 999[9]',
                                greedy: true
                            }}
                            placeholder="Credit card number"
                            className="block w-full"
                            onChange={() => {
                                removeError('credit_card_number');

                                updateInstallments();
                            }}
                            required
                        />

                        <InputError message={errors.credit_card_number} className="mt-2" />
                    </div>

                    <div>
                        <TextInput
                            type="text"
                            name="holder_name"
                            placeholder="Holder name"
                            className="block w-full"
                            onChange={() => removeError('holder_name')}
                            required
                        />

                        <InputError message={errors.holder_name} className="mt-2" />
                    </div>

                    <div>
                        <TextInput
                            type="text"
                            name="expiration_month"
                            mask={{
                                regex: '(0[1-9]|1[1,2])',
                            }}
                            placeholder="Expiration month"
                            className="block w-full"
                            onChange={() => removeError('expiration_month')}
                            required
                        />

                        <InputError message={errors.expiration_month} className="mt-2" />
                    </div>

                    <div>
                        <TextInput
                            type="text"
                            name="expiration_year"
                            mask={{
                                mask: '2099',
                            }}
                            placeholder="Expiration year"
                            className="block w-full"
                            onChange={() => removeError('expiration_year')}
                            required
                        />

                        <InputError message={errors.expiration_year} className="mt-2" />
                    </div>

                    <div>
                        <TextInput
                            type="text"
                            name="security_code"
                            mask={{
                                mask: '999',
                            }}
                            placeholder="CVV"
                            className="block w-full"
                            onChange={() => removeError('security_code')}
                            required
                        />

                        <InputError message={errors.security_code} className="mt-2" />
                    </div>

                    <div>
                        <SelectInput
                            name="installments"
                            options={installmentOptions}
                            className="block w-full"
                            onChange={() => removeError('installments')}
                            required
                        />

                        <InputError message={errors.installments} className="mt-2" />
                    </div>
                </div>
            </div>
            <SuccessButton
                disabled={disabled || processing}
                className={`w-full mt-10 flex justify-center ` + (disabled ? 'bg-gray-500' : '')}
            >
                {
                    processing
                        ? <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 animate-spin" fill="currentColor" viewBox="0 0 16 16">
                            <path fillRule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z" />
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466" />
                        </svg>
                        : 'Pay'
                }
            </SuccessButton>
        </form>
    );
}
