import { redirect } from "next/navigation";

// /category/[slug] is kept as an alias of the fully-working product listing at
// /search/[slug] (which resolves the slug via ?category_slug). Preserves query.
export default async function CategoryPage({ params, searchParams }) {
  const { slug } = await params;
  const sp = (await searchParams) || {};

  const query = new URLSearchParams(
    Object.fromEntries(
      Object.entries(sp).filter(([, v]) => v != null && v !== "")
    )
  ).toString();

  redirect(`/search/${encodeURIComponent(slug)}${query ? `?${query}` : ""}`);
}
