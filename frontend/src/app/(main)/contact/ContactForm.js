"use client";

import React, { useState } from "react";
import { CircleAlert, Send } from "lucide-react";

const SUPPORT_EMAIL = "support@hikala.example";

const INPUT_BASE =
  "w-full rounded-lg border bg-white px-3.5 py-2.5 text-sm text-neutral-800 placeholder:text-neutral-400 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:bg-neutral-950/40 dark:text-neutral-100 dark:placeholder:text-neutral-500";

function inputClass(hasError) {
  return hasError
    ? `${INPUT_BASE} border-red-400 dark:border-red-600`
    : `${INPUT_BASE} border-neutral-200 dark:border-neutral-800`;
}

function FieldError({ message }) {
  if (!message) return null;

  return (
    <p
      role="alert"
      className="mt-1.5 flex items-start gap-1.5 rounded-lg border border-red-200 bg-red-50/70 px-2.5 py-1.5 text-[11px] font-medium leading-5 text-red-600 dark:border-red-900 dark:bg-red-950/30 dark:text-red-400"
    >
      <CircleAlert aria-hidden="true" className="mt-0.5 h-3.5 w-3.5 shrink-0" />
      <span>{message}</span>
    </p>
  );
}

export default function ContactForm() {
  const [values, setValues] = useState({
    name: "",
    email: "",
    subject: "",
    message: "",
  });
  const [errors, setErrors] = useState({});
  const [opened, setOpened] = useState(false);

  function handleChange(event) {
    const { name, value } = event.target;

    setValues((current) => ({ ...current, [name]: value }));
    setOpened(false);

    if (errors[name]) {
      setErrors((current) => ({ ...current, [name]: undefined }));
    }
  }

  function validate() {
    const nextErrors = {};

    if (!values.name.trim()) {
      nextErrors.name = "Please enter your full name.";
    }

    if (!values.email.trim()) {
      nextErrors.email = "Please enter your email address.";
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(values.email.trim())) {
      nextErrors.email = "Please enter a valid email address, like name@example.com.";
    }

    if (!values.subject.trim()) {
      nextErrors.subject = "Please add a short subject.";
    }

    if (!values.message.trim()) {
      nextErrors.message = "Please describe your question or request.";
    } else if (values.message.trim().length < 10) {
      nextErrors.message =
        "Please add a little more detail (at least 10 characters) so we can help faster.";
    }

    return nextErrors;
  }

  function handleSubmit(event) {
    event.preventDefault();

    const nextErrors = validate();
    setErrors(nextErrors);

    if (Object.keys(nextErrors).length > 0) {
      setOpened(false);
      return;
    }

    const subjectLine = `Hikala contact form: ${values.subject.trim()}`;
    const bodyLines = [
      `Name: ${values.name.trim()}`,
      `Email: ${values.email.trim()}`,
      `Subject: ${values.subject.trim()}`,
      "",
      values.message.trim(),
    ].join("\n");

    const href = `mailto:${SUPPORT_EMAIL}?subject=${encodeURIComponent(
      subjectLine,
    )}&body=${encodeURIComponent(bodyLines)}`;

    window.location.href = href;
    setOpened(true);
  }

  return (
    <form noValidate onSubmit={handleSubmit} className="mt-5 space-y-4">
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <label
            htmlFor="contact-name"
            className="mb-1.5 block text-xs font-bold text-neutral-700 dark:text-neutral-200"
          >
            Full name
          </label>
          <input
            id="contact-name"
            name="name"
            type="text"
            autoComplete="name"
            placeholder="Jane Cooper"
            value={values.name}
            onChange={handleChange}
            aria-invalid={errors.name ? "true" : "false"}
            className={inputClass(Boolean(errors.name))}
          />
          <FieldError message={errors.name} />
        </div>

        <div>
          <label
            htmlFor="contact-email"
            className="mb-1.5 block text-xs font-bold text-neutral-700 dark:text-neutral-200"
          >
            Email address
          </label>
          <input
            id="contact-email"
            name="email"
            type="email"
            autoComplete="email"
            placeholder="you@example.com"
            value={values.email}
            onChange={handleChange}
            aria-invalid={errors.email ? "true" : "false"}
            className={inputClass(Boolean(errors.email))}
          />
          <FieldError message={errors.email} />
        </div>
      </div>

      <div>
        <label
          htmlFor="contact-subject"
          className="mb-1.5 block text-xs font-bold text-neutral-700 dark:text-neutral-200"
        >
          Subject
        </label>
        <input
          id="contact-subject"
          name="subject"
          type="text"
          placeholder="Order #1024 delivery question"
          value={values.subject}
          onChange={handleChange}
          aria-invalid={errors.subject ? "true" : "false"}
          className={inputClass(Boolean(errors.subject))}
        />
        <FieldError message={errors.subject} />
      </div>

      <div>
        <label
          htmlFor="contact-message"
          className="mb-1.5 block text-xs font-bold text-neutral-700 dark:text-neutral-200"
        >
          Message
        </label>
        <textarea
          id="contact-message"
          name="message"
          rows={5}
          placeholder="Tell us what happened and what you would like us to do."
          value={values.message}
          onChange={handleChange}
          aria-invalid={errors.message ? "true" : "false"}
          className={`${inputClass(Boolean(errors.message))} resize-y`}
        />
        <FieldError message={errors.message} />
      </div>

      <div className="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p className="text-[11px] leading-5 text-neutral-500 dark:text-neutral-400">
          Submitting opens your own email app with this message pre-filled. It
          is only sent when you press send there.
        </p>

        <button
          type="submit"
          className="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-lg bg-red-500 px-6 text-sm font-bold text-white transition-colors hover:bg-red-600 active:scale-[0.98] focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-neutral-900"
        >
          <Send aria-hidden="true" className="h-4 w-4" />
          <span>Compose message</span>
        </button>
      </div>

      {opened ? (
        <p className="flex items-start gap-2 rounded-lg border border-neutral-200 bg-neutral-50/60 p-3 text-[11px] leading-5 text-neutral-600 dark:border-neutral-800 dark:bg-neutral-950/30 dark:text-neutral-300">
          <span className="mt-0.5 h-2 w-2 shrink-0 rounded-full bg-blue-500" />
          <span>
            Your email app should now be open with the message ready to send. If
            nothing happened, write to us directly at{" "}
            <a
              href={`mailto:${SUPPORT_EMAIL}`}
              className="font-bold text-blue-500 transition-colors hover:text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 dark:text-blue-400"
            >
              {SUPPORT_EMAIL}
            </a>
            .
          </span>
        </p>
      ) : null}
    </form>
  );
}
