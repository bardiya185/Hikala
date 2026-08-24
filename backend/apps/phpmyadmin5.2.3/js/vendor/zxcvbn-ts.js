this.zxcvbnts = this.zxcvbnts || {};
this.zxcvbnts.core = (function (exports) {
    "use strict";

    const empty = (obj) => Object.keys(obj).length === 0;
    const extend = (listToExtend, list) =>
        listToExtend.push.apply(listToExtend, list);
    const translate = (string, chrMap) => {
        const tempArray = string.split("");
        return tempArray.map((char) => chrMap[char] || char).join("");
    };
    const sorted = (matches) =>
        matches.sort((m1, m2) => m1.i - m2.i || m1.j - m2.j);
    const buildRankedDictionary = (orderedList) => {
        const result = {};
        let counter = 1; // rank starts at 1, not 0
        orderedList.forEach((word) => {
            result[word] = counter;
            counter += 1;
        });
        return result;
    };

    var dateSplits = {
        4: [
            [1, 2],
            [2, 3], // 91 1 1
        ],

        5: [
            [1, 3],
            [2, 3],
            [2, 4], // 91 11 1    <- New and must be added as bug fix
        ],

        6: [
            [1, 2],
            [2, 4],
            [4, 5], // 1991 1 1
        ],
        7: [
            [1, 3],
            [2, 3],
            [4, 5],
            [4, 6], // 1991 11 1
        ],

        8: [
            [2, 4],
            [4, 6], // 1991 11 11
        ],
    };

    const DATE_MAX_YEAR = 2050;
    const DATE_MIN_YEAR = 1000;
    const DATE_SPLITS = dateSplits;
    const BRUTEFORCE_CARDINALITY = 10;
    const MIN_GUESSES_BEFORE_GROWING_SEQUENCE = 10000;
    const MIN_SUBMATCH_GUESSES_SINGLE_CHAR = 10;
    const MIN_SUBMATCH_GUESSES_MULTI_CHAR = 50;
    const MIN_YEAR_SPACE = 20;
    const START_UPPER = /^[A-Z\xbf-\xdf][^A-Z\xbf-\xdf]+$/;
    const END_UPPER = /^[^A-Z\xbf-\xdf]+[A-Z\xbf-\xdf]$/;
    const ALL_UPPER = /^[A-Z\xbf-\xdf]+$/;
    const ALL_UPPER_INVERTED = /^[^a-z\xdf-\xff]+$/;
    const ALL_LOWER = /^[a-z\xdf-\xff]+$/;
    const ALL_LOWER_INVERTED = /^[^A-Z\xbf-\xdf]+$/;
    const ONE_LOWER = /[a-z\xdf-\xff]/;
    const ONE_UPPER = /[A-Z\xbf-\xdf]/;
    const ALPHA_INVERTED = /[^A-Za-z\xbf-\xdf]/gi;
    const ALL_DIGIT = /^\d+$/;
    const REFERENCE_YEAR = new Date().getFullYear();
    const REGEXEN = {
        recentYear: /19\d\d|200\d|201\d|202\d/g,
    };

    /*
     * -------------------------------------------------------------------------------
     *  date matching ----------------------------------------------------------------
     * -------------------------------------------------------------------------------
     */
    class MatchDate {
        /*
         * a "date" is recognized as:
         *   any 3-tuple that starts or ends with a 2- or 4-digit year,
         *   with 2 or 0 separator chars (1.1.91 or 1191),
         *   maybe zero-padded (01-01-91 vs 1-1-91),
         *   a month between 1 and 12,
         *   a day between 1 and 31.
         *
         * note: this isn't true date parsing in that "feb 31st" is allowed,
         * this doesn't check for leap years, etc.
         *
         * recipe:
         * start with regex to find maybe-dates, then attempt to map the integers
         * onto month-day-year to filter the maybe-dates into dates.
         * finally, remove matches that are substrings of other matches to reduce noise.
         *
         * note: instead of using a lazy or greedy regex to find many dates over the full string,
         * this uses a ^...$ regex against every substring of the password -- less performant but leads
         * to every possible date match.
         */
        match({ password }) {
            const matches = [
                ...this.getMatchesWithoutSeparator(password),
                ...this.getMatchesWithSeparator(password),
            ];
            const filteredMatches = this.filterNoise(matches);
            return sorted(filteredMatches);
        }
        getMatchesWithSeparator(password) {
            const matches = [];
            const maybeDateWithSeparator =
                /^(\d{1,4})([\s/\\_.-])(\d{1,2})\2(\d{1,4})$/;
            for (let i = 0; i <= Math.abs(password.length - 6); i += 1) {
                for (let j = i + 5; j <= i + 9; j += 1) {
                    if (j >= password.length) {
                        break;
                    }
                    const token = password.slice(i, +j + 1 || 9e9);
                    const regexMatch = maybeDateWithSeparator.exec(token);
                    if (regexMatch != null) {
                        const dmy = this.mapIntegersToDayMonthYear([
                            parseInt(regexMatch[1], 10),
                            parseInt(regexMatch[3], 10),
                            parseInt(regexMatch[4], 10),
                        ]);
                        if (dmy != null) {
                            matches.push({
                                pattern: "date",
                                token,
                                i,
                                j,
                                separator: regexMatch[2],
                                year: dmy.year,
                                month: dmy.month,
                                day: dmy.day,
                            });
                        }
                    }
                }
            }
            return matches;
        }
        getMatchesWithoutSeparator(password) {
            const matches = [];
            const maybeDateNoSeparator = /^\d{4,8}$/;
            const metric = (candidate) =>
                Math.abs(candidate.year - REFERENCE_YEAR);
            for (let i = 0; i <= Math.abs(password.length - 4); i += 1) {
                for (let j = i + 3; j <= i + 7; j += 1) {
                    if (j >= password.length) {
                        break;
                    }
                    const token = password.slice(i, +j + 1 || 9e9);
                    if (maybeDateNoSeparator.exec(token)) {
                        const candidates = [];
                        const index = token.length;
                        const splittedDates = DATE_SPLITS[index];
                        splittedDates.forEach(([k, l]) => {
                            const dmy = this.mapIntegersToDayMonthYear([
                                parseInt(token.slice(0, k), 10),
                                parseInt(token.slice(k, l), 10),
                                parseInt(token.slice(l), 10),
                            ]);
                            if (dmy != null) {
                                candidates.push(dmy);
                            }
                        });
                        if (candidates.length > 0) {
                            /*
                             * at this point: different possible dmy mappings for the same i,j substring.
                             * match the candidate date that likely takes the fewest guesses: a year closest
                             * to 2000.
                             * (scoring.REFERENCE_YEAR).
                             *
                             * ie, considering '111504', prefer 11-15-04 to 1-1-1504
                             * (interpreting '04' as 2004)
                             */
                            let bestCandidate = candidates[0];
                            let minDistance = metric(candidates[0]);
                            candidates.slice(1).forEach((candidate) => {
                                const distance = metric(candidate);
                                if (distance < minDistance) {
                                    bestCandidate = candidate;
                                    minDistance = distance;
                                }
                            });
                            matches.push({
                                pattern: "date",
                                token,
                                i,
                                j,
                                separator: "",
                                year: bestCandidate.year,
                                month: bestCandidate.month,
                                day: bestCandidate.day,
                            });
                        }
                    }
                }
            }
            return matches;
        }
        /*
         * matches now contains all valid date strings in a way that is tricky to capture
         * with regexes only. while thorough, it will contain some unintuitive noise:
         *
         * '2015_06_04', in addition to matching 2015_06_04, will also contain
         * 5(!) other date matches: 15_06_04, 5_06_04, ..., even 2015 (matched as 5/1/2020)
         *
         * to reduce noise, remove date matches that are strict substrings of others
         */
        filterNoise(matches) {
            return matches.filter((match) => {
                let isSubmatch = false;
                const matchesLength = matches.length;
                for (let o = 0; o < matchesLength; o += 1) {
                    const otherMatch = matches[o];
                    if (match !== otherMatch) {
                        if (
                            otherMatch.i <= match.i &&
                            otherMatch.j >= match.j
                        ) {
                            isSubmatch = true;
                            break;
                        }
                    }
                }
                return !isSubmatch;
            });
        }
        /*
         * given a 3-tuple, discard if:
         *   middle int is over 31 (for all dmy formats, years are never allowed in the middle)
         *   middle int is zero
         *   any int is over the max allowable year
         *   any int is over two digits but under the min allowable year
         *   2 integers are over 31, the max allowable day
         *   2 integers are zero
         *   all integers are over 12, the max allowable month
         */
        mapIntegersToDayMonthYear(integers) {
            if (integers[1] > 31 || integers[1] <= 0) {
                return null;
            }
            let over12 = 0;
            let over31 = 0;
            let under1 = 0;
            for (let o = 0, len1 = integers.length; o < len1; o += 1) {
                const int = integers[o];
                if ((int > 99 && int < DATE_MIN_YEAR) || int > DATE_MAX_YEAR) {
                    return null;
                }
                if (int > 31) {
                    over31 += 1;
                }
                if (int > 12) {
                    over12 += 1;
                }
                if (int <= 0) {
                    under1 += 1;
                }
            }
            if (over31 >= 2 || over12 === 3 || under1 >= 2) {
                return null;
            }
            return this.getDayMonth(integers);
        }
        getDayMonth(integers) {
            const possibleYearSplits = [
                [integers[2], integers.slice(0, 2)],
                [integers[0], integers.slice(1, 3)], // year first
            ];

            const possibleYearSplitsLength = possibleYearSplits.length;
            for (let j = 0; j < possibleYearSplitsLength; j += 1) {
                const [y, rest] = possibleYearSplits[j];
                if (DATE_MIN_YEAR <= y && y <= DATE_MAX_YEAR) {
                    const dm = this.mapIntegersToDayMonth(rest);
                    if (dm != null) {
                        return {
                            year: y,
                            month: dm.month,
                            day: dm.day,
                        };
                    }
                    /*
                     * for a candidate that includes a four-digit year,
                     * when the remaining integers don't match to a day and month,
                     * it is not a date.
                     */
                    return null;
                }
            }
            for (let k = 0; k < possibleYearSplitsLength; k += 1) {
                const [y, rest] = possibleYearSplits[k];
                const dm = this.mapIntegersToDayMonth(rest);
                if (dm != null) {
                    return {
                        year: this.twoToFourDigitYear(y),
                        month: dm.month,
                        day: dm.day,
                    };
                }
            }
            return null;
        }
        mapIntegersToDayMonth(integers) {
            const temp = [integers, integers.slice().reverse()];
            for (let i = 0; i < temp.length; i += 1) {
                const data = temp[i];
                const day = data[0];
                const month = data[1];
                if (day >= 1 && day <= 31 && month >= 1 && month <= 12) {
                    return {
                        day,
                        month,
                    };
                }
            }
            return null;
        }
        twoToFourDigitYear(year) {
            if (year > 99) {
                return year;
            }
            if (year > 50) {
                return year + 1900;
            }
            return year + 2000;
        }
    }

    const peq = new Uint32Array(0x10000);
    const myers_32 = (a, b) => {
        const n = a.length;
        const m = b.length;
        const lst = 1 << (n - 1);
        let pv = -1;
        let mv = 0;
        let sc = n;
        let i = n;
        while (i--) {
            peq[a.charCodeAt(i)] |= 1 << i;
        }
        for (i = 0; i < m; i++) {
            let eq = peq[b.charCodeAt(i)];
            const xv = eq | mv;
            eq |= ((eq & pv) + pv) ^ pv;
            mv |= ~(eq | pv);
            pv &= eq;
            if (mv & lst) {
                sc++;
            }
            if (pv & lst) {
                sc--;
            }
            mv = (mv << 1) | 1;
            pv = (pv << 1) | ~(xv | mv);
            mv &= xv;
        }
        i = n;
        while (i--) {
            peq[a.charCodeAt(i)] = 0;
        }
        return sc;
    };
    const myers_x = (b, a) => {
        const n = a.length;
        const m = b.length;
        const mhc = [];
        const phc = [];
        const hsize = Math.ceil(n / 32);
        const vsize = Math.ceil(m / 32);
        for (let i = 0; i < hsize; i++) {
            phc[i] = -1;
            mhc[i] = 0;
        }
        let j = 0;
        for (; j < vsize - 1; j++) {
            let mv = 0;
            let pv = -1;
            const start = j * 32;
            const vlen = Math.min(32, m) + start;
            for (let k = start; k < vlen; k++) {
                peq[b.charCodeAt(k)] |= 1 << k;
            }
            for (let i = 0; i < n; i++) {
                const eq = peq[a.charCodeAt(i)];
                const pb = (phc[(i / 32) | 0] >>> i) & 1;
                const mb = (mhc[(i / 32) | 0] >>> i) & 1;
                const xv = eq | mv;
                const xh = ((((eq | mb) & pv) + pv) ^ pv) | eq | mb;
                let ph = mv | ~(xh | pv);
                let mh = pv & xh;
                if ((ph >>> 31) ^ pb) {
                    phc[(i / 32) | 0] ^= 1 << i;
                }
                if ((mh >>> 31) ^ mb) {
                    mhc[(i / 32) | 0] ^= 1 << i;
                }
                ph = (ph << 1) | pb;
                mh = (mh << 1) | mb;
                pv = mh | ~(xv | ph);
                mv = ph & xv;
            }
            for (let k = start; k < vlen; k++) {
                peq[b.charCodeAt(k)] = 0;
            }
        }
        let mv = 0;
        let pv = -1;
        const start = j * 32;
        const vlen = Math.min(32, m - start) + start;
        for (let k = start; k < vlen; k++) {
            peq[b.charCodeAt(k)] |= 1 << k;
        }
        let score = m;
        for (let i = 0; i < n; i++) {
            const eq = peq[a.charCodeAt(i)];
            const pb = (phc[(i / 32) | 0] >>> i) & 1;
            const mb = (mhc[(i / 32) | 0] >>> i) & 1;
            const xv = eq | mv;
            const xh = ((((eq | mb) & pv) + pv) ^ pv) | eq | mb;
            let ph = mv | ~(xh | pv);
            let mh = pv & xh;
            score += (ph >>> (m - 1)) & 1;
            score -= (mh >>> (m - 1)) & 1;
            if ((ph >>> 31) ^ pb) {
                phc[(i / 32) | 0] ^= 1 << i;
            }
            if ((mh >>> 31) ^ mb) {
                mhc[(i / 32) | 0] ^= 1 << i;
            }
            ph = (ph << 1) | pb;
            mh = (mh << 1) | mb;
            pv = mh | ~(xv | ph);
            mv = ph & xv;
        }
        for (let k = start; k < vlen; k++) {
            peq[b.charCodeAt(k)] = 0;
        }
        return score;
    };
    const distance = (a, b) => {
        if (a.length < b.length) {
            const tmp = b;
            b = a;
            a = tmp;
        }
        if (b.length === 0) {
            return a.length;
        }
        if (a.length <= 32) {
            return myers_32(a, b);
        }
        return myers_x(a, b);
    };

    const getUsedThreshold = (password, entry, threshold) => {
        const isPasswordToShort = password.length <= entry.length;
        const isThresholdLongerThanPassword = password.length <= threshold;
        const shouldUsePasswordLength =
            isPasswordToShort || isThresholdLongerThanPassword;
        return shouldUsePasswordLength
            ? Math.ceil(password.length / 4)
            : threshold;
    };
    const findLevenshteinDistance = (password, rankedDictionary, threshold) => {
        let foundDistance = 0;
        const found = Object.keys(rankedDictionary).find((entry) => {
            const usedThreshold = getUsedThreshold(password, entry, threshold);
            const foundEntryDistance = distance(password, entry);
            const isInThreshold = foundEntryDistance <= usedThreshold;
            if (isInThreshold) {
                foundDistance = foundEntryDistance;
            }
            return isInThreshold;
        });
        if (found) {
            return {
                levenshteinDistance: foundDistance,
                levenshteinDistanceEntry: found,
            };
        }
        return {};
    };

    var l33tTable = {
        a: ["4", "@"],
        b: ["8"],
        c: ["(", "{", "[", "<"],
        e: ["3"],
        g: ["6", "9"],
        i: ["1", "!", "|"],
        l: ["1", "|", "7"],
        o: ["0"],
        s: ["$", "5"],
        t: ["+", "7"],
        x: ["%"],
        z: ["2"],
    };

    var translationKeys = {
        warnings: {
            straightRow: "straightRow",
            keyPattern: "keyPattern",
            simpleRepeat: "simpleRepeat",
            extendedRepeat: "extendedRepeat",
            sequences: "sequences",
            recentYears: "recentYears",
            dates: "dates",
            topTen: "topTen",
            topHundred: "topHundred",
            common: "common",
            similarToCommon: "similarToCommon",
            wordByItself: "wordByItself",
            namesByThemselves: "namesByThemselves",
            commonNames: "commonNames",
            userInputs: "userInputs",
            pwned: "pwned",
        },
        suggestions: {
            l33t: "l33t",
            reverseWords: "reverseWords",
            allUppercase: "allUppercase",
            capitalization: "capitalization",
            dates: "dates",
            recentYears: "recentYears",
            associatedYears: "associatedYears",
            sequences: "sequences",
            repeated: "repeated",
            longerKeyboardPattern: "longerKeyboardPattern",
            anotherWord: "anotherWord",
            useWords: "useWords",
            noNeed: "noNeed",
            pwned: "pwned",
        },
        timeEstimation: {
            ltSecond: "ltSecond",
            second: "second",
            seconds: "seconds",
            minute: "minute",
            minutes: "minutes",
            hour: "hour",
            hours: "hours",
            day: "day",
            days: "days",
            month: "month",
            months: "months",
            year: "year",
            years: "years",
            centuries: "centuries",
        },
    };

    class Options {
        constructor() {
            this.matchers = {};
            this.l33tTable = l33tTable;
            this.dictionary = {
                userInputs: [],
            };
            this.rankedDictionaries = {};
            this.rankedDictionariesMaxWordSize = {};
            this.translations = translationKeys;
            this.graphs = {};
            this.useLevenshteinDistance = false;
            this.levenshteinThreshold = 2;
            this.l33tMaxSubstitutions = 100;
            this.maxLength = 256;
            this.setRankedDictionaries();
        }
        setOptions(options = {}) {
            if (options.l33tTable) {
                this.l33tTable = options.l33tTable;
            }
            if (options.dictionary) {
                this.dictionary = options.dictionary;
                this.setRankedDictionaries();
            }
            if (options.translations) {
                this.setTranslations(options.translations);
            }
            if (options.graphs) {
                this.graphs = options.graphs;
            }
            if (options.useLevenshteinDistance !== undefined) {
                this.useLevenshteinDistance = options.useLevenshteinDistance;
            }
            if (options.levenshteinThreshold !== undefined) {
                this.levenshteinThreshold = options.levenshteinThreshold;
            }
            if (options.l33tMaxSubstitutions !== undefined) {
                this.l33tMaxSubstitutions = options.l33tMaxSubstitutions;
            }
            if (options.maxLength !== undefined) {
                this.maxLength = options.maxLength;
            }
        }
        setTranslations(translations) {
            if (this.checkCustomTranslations(translations)) {
                this.translations = translations;
            } else {
                throw new Error("Invalid translations object fallback to keys");
            }
        }
        checkCustomTranslations(translations) {
            let valid = true;
            Object.keys(translationKeys).forEach((type) => {
                if (type in translations) {
                    const translationType = type;
                    Object.keys(translationKeys[translationType]).forEach(
                        (key) => {
                            if (!(key in translations[translationType])) {
                                valid = false;
                            }
                        },
                    );
                } else {
                    valid = false;
                }
            });
            return valid;
        }
        setRankedDictionaries() {
            const rankedDictionaries = {};
            const rankedDictionariesMaxWorkSize = {};
            Object.keys(this.dictionary).forEach((name) => {
                rankedDictionaries[name] = this.getRankedDictionary(name);
                rankedDictionariesMaxWorkSize[name] =
                    this.getRankedDictionariesMaxWordSize(name);
            });
            this.rankedDictionaries = rankedDictionaries;
            this.rankedDictionariesMaxWordSize = rankedDictionariesMaxWorkSize;
        }
        getRankedDictionariesMaxWordSize(name) {
            const data = this.dictionary[name].map((el) => {
                if (typeof el !== "string") {
                    return el.toString().length;
                }
                return el.length;
            });
            if (data.length === 0) {
                return 0;
            }
            return data.reduce((a, b) => Math.max(a, b), -Infinity);
        }
        getRankedDictionary(name) {
            const list = this.dictionary[name];
            if (name === "userInputs") {
                const sanitizedInputs = [];
                list.forEach((input) => {
                    const inputType = typeof input;
                    if (
                        inputType === "string" ||
                        inputType === "number" ||
                        inputType === "boolean"
                    ) {
                        sanitizedInputs.push(input.toString().toLowerCase());
                    }
                });
                return buildRankedDictionary(sanitizedInputs);
            }
            return buildRankedDictionary(list);
        }
        extendUserInputsDictionary(dictionary) {
            if (this.dictionary.userInputs) {
                this.dictionary.userInputs = [
                    ...this.dictionary.userInputs,
                    ...dictionary,
                ];
            } else {
                this.dictionary.userInputs = dictionary;
            }
            this.rankedDictionaries.userInputs =
                this.getRankedDictionary("userInputs");
            this.rankedDictionariesMaxWordSize.userInputs =
                this.getRankedDictionariesMaxWordSize("userInputs");
        }
        addMatcher(name, matcher) {
            if (this.matchers[name]) {
                console.info(`Matcher ${name} already exists`);
            } else {
                this.matchers[name] = matcher;
            }
        }
    }
    const zxcvbnOptions = new Options();

    /*
     * -------------------------------------------------------------------------------
     *  Dictionary reverse matching --------------------------------------------------
     * -------------------------------------------------------------------------------
     */
    class MatchReverse {
        constructor(defaultMatch) {
            this.defaultMatch = defaultMatch;
        }
        match({ password }) {
            const passwordReversed = password.split("").reverse().join("");
            return this.defaultMatch({
                password: passwordReversed,
            }).map((match) => ({
                ...match,
                token: match.token.split("").reverse().join(""),
                reversed: true,
                i: password.length - 1 - match.j,
                j: password.length - 1 - match.i,
            }));
        }
    }

    /*
     * -------------------------------------------------------------------------------
     *  Dictionary l33t matching -----------------------------------------------------
     * -------------------------------------------------------------------------------
     */
    class MatchL33t {
        constructor(defaultMatch) {
            this.defaultMatch = defaultMatch;
        }
        match({ password }) {
            const matches = [];
            const enumeratedSubs = this.enumerateL33tSubs(
                this.relevantL33tSubtable(password, zxcvbnOptions.l33tTable),
            );
            const length = Math.min(
                enumeratedSubs.length,
                zxcvbnOptions.l33tMaxSubstitutions,
            );
            for (let i = 0; i < length; i += 1) {
                const sub = enumeratedSubs[i];
                if (empty(sub)) {
                    break;
                }
                const subbedPassword = translate(password, sub);
                const matchedDictionary = this.defaultMatch({
                    password: subbedPassword,
                });
                matchedDictionary.forEach((match) => {
                    const token = password.slice(match.i, +match.j + 1 || 9e9);
                    if (token.toLowerCase() !== match.matchedWord) {
                        const matchSub = {};
                        Object.keys(sub).forEach((subbedChr) => {
                            const chr = sub[subbedChr];
                            if (token.indexOf(subbedChr) !== -1) {
                                matchSub[subbedChr] = chr;
                            }
                        });
                        const subDisplay = Object.keys(matchSub)
                            .map((k) => `${k} -> ${matchSub[k]}`)
                            .join(", ");
                        matches.push({
                            ...match,
                            l33t: true,
                            token,
                            sub: matchSub,
                            subDisplay,
                        });
                    }
                });
            }
            return matches.filter((match) => match.token.length > 1);
        }
        relevantL33tSubtable(password, table) {
            const passwordChars = {};
            const subTable = {};
            password.split("").forEach((char) => {
                passwordChars[char] = true;
            });
            Object.keys(table).forEach((letter) => {
                const subs = table[letter];
                const relevantSubs = subs.filter((sub) => sub in passwordChars);
                if (relevantSubs.length > 0) {
                    subTable[letter] = relevantSubs;
                }
            });
            return subTable;
        }
        enumerateL33tSubs(table) {
            const tableKeys = Object.keys(table);
            const subs = this.getSubs(tableKeys, [[]], table);
            return subs.map((sub) => {
                const subDict = {};
                sub.forEach(([l33tChr, chr]) => {
                    subDict[l33tChr] = chr;
                });
                return subDict;
            });
        }
        getSubs(keys, subs, table) {
            if (!keys.length) {
                return subs;
            }
            const firstKey = keys[0];
            const restKeys = keys.slice(1);
            const nextSubs = [];
            table[firstKey].forEach((l33tChr) => {
                subs.forEach((sub) => {
                    let dupL33tIndex = -1;
                    for (let i = 0; i < sub.length; i += 1) {
                        if (sub[i][0] === l33tChr) {
                            dupL33tIndex = i;
                            break;
                        }
                    }
                    if (dupL33tIndex === -1) {
                        const subExtension = sub.concat([[l33tChr, firstKey]]);
                        nextSubs.push(subExtension);
                    } else {
                        const subAlternative = sub.slice(0);
                        subAlternative.splice(dupL33tIndex, 1);
                        subAlternative.push([l33tChr, firstKey]);
                        nextSubs.push(sub);
                        nextSubs.push(subAlternative);
                    }
                });
            });
            const newSubs = this.dedup(nextSubs);
            if (restKeys.length) {
                return this.getSubs(restKeys, newSubs, table);
            }
            return newSubs;
        }
        dedup(subs) {
            const deduped = [];
            const members = {};
            subs.forEach((sub) => {
                const assoc = sub.map((k, index) => [k, index]);
                assoc.sort();
                const label = assoc.map(([k, v]) => `${k},${v}`).join("-");
                if (!(label in members)) {
                    members[label] = true;
                    deduped.push(sub);
                }
            });
            return deduped;
        }
    }

    class MatchDictionary {
        constructor() {
            this.l33t = new MatchL33t(this.defaultMatch);
            this.reverse = new MatchReverse(this.defaultMatch);
        }
        match({ password }) {
            const matches = [
                ...this.defaultMatch({
                    password,
                }),
                ...this.reverse.match({
                    password,
                }),
                ...this.l33t.match({
                    password,
                }),
            ];
            return sorted(matches);
        }
        defaultMatch({ password }) {
            const matches = [];
            const passwordLength = password.length;
            const passwordLower = password.toLowerCase();
            Object.keys(zxcvbnOptions.rankedDictionaries).forEach(
                (dictionaryName) => {
                    const rankedDict =
                        zxcvbnOptions.rankedDictionaries[dictionaryName];
                    const longestDictionaryWordSize =
                        zxcvbnOptions.rankedDictionariesMaxWordSize[
                            dictionaryName
                        ];
                    const searchWidth = Math.min(
                        longestDictionaryWordSize,
                        passwordLength,
                    );
                    for (let i = 0; i < passwordLength; i += 1) {
                        const searchEnd = Math.min(
                            i + searchWidth,
                            passwordLength,
                        );
                        for (let j = i; j < searchEnd; j += 1) {
                            const usedPassword = passwordLower.slice(
                                i,
                                +j + 1 || 9e9,
                            );
                            const isInDictionary = usedPassword in rankedDict;
                            let foundLevenshteinDistance = {};
                            const isFullPassword =
                                i === 0 && j === passwordLength - 1;
                            if (
                                zxcvbnOptions.useLevenshteinDistance &&
                                isFullPassword &&
                                !isInDictionary
                            ) {
                                foundLevenshteinDistance =
                                    findLevenshteinDistance(
                                        usedPassword,
                                        rankedDict,
                                        zxcvbnOptions.levenshteinThreshold,
                                    );
                            }
                            const isLevenshteinMatch =
                                Object.keys(foundLevenshteinDistance).length !==
                                0;
                            if (isInDictionary || isLevenshteinMatch) {
                                const usedRankPassword = isLevenshteinMatch
                                    ? foundLevenshteinDistance.levenshteinDistanceEntry
                                    : usedPassword;
                                const rank = rankedDict[usedRankPassword];
                                matches.push({
                                    pattern: "dictionary",
                                    i,
                                    j,
                                    token: password.slice(i, +j + 1 || 9e9),
                                    matchedWord: usedPassword,
                                    rank,
                                    dictionaryName: dictionaryName,
                                    reversed: false,
                                    l33t: false,
                                    ...foundLevenshteinDistance,
                                });
                            }
                        }
                    }
                },
            );
            return matches;
        }
    }

    /*
     * -------------------------------------------------------------------------------
     *  regex matching ---------------------------------------------------------------
     * -------------------------------------------------------------------------------
     */
    class MatchRegex {
        match({ password, regexes = REGEXEN }) {
            const matches = [];
            Object.keys(regexes).forEach((name) => {
                const regex = regexes[name];
                regex.lastIndex = 0; // keeps regexMatch stateless
                const regexMatch = regex.exec(password);
                if (regexMatch) {
                    const token = regexMatch[0];
                    matches.push({
                        pattern: "regex",
                        token,
                        i: regexMatch.index,
                        j: regexMatch.index + regexMatch[0].length - 1,
                        regexName: name,
                        regexMatch,
                    });
                }
            });
            return sorted(matches);
        }
    }

    var utils = {
        nCk(n, k) {
            let count = n;
            if (k > count) {
                return 0;
            }
            if (k === 0) {
                return 1;
            }
            let coEff = 1;
            for (let i = 1; i <= k; i += 1) {
                coEff *= count;
                coEff /= i;
                count -= 1;
            }
            return coEff;
        },
        log10(n) {
            return Math.log(n) / Math.log(10); // IE doesn't support Math.log10 :(
        },

        log2(n) {
            return Math.log(n) / Math.log(2);
        },
        factorial(num) {
            let rval = 1;
            for (let i = 2; i <= num; i += 1) rval *= i;
            return rval;
        },
    };

    var bruteforceMatcher$1 = ({ token }) => {
        let guesses = BRUTEFORCE_CARDINALITY ** token.length;
        if (guesses === Number.POSITIVE_INFINITY) {
            guesses = Number.MAX_VALUE;
        }
        let minGuesses;
        if (token.length === 1) {
            minGuesses = MIN_SUBMATCH_GUESSES_SINGLE_CHAR + 1;
        } else {
            minGuesses = MIN_SUBMATCH_GUESSES_MULTI_CHAR + 1;
        }
        return Math.max(guesses, minGuesses);
    };

    var dateMatcher$1 = ({ year, separator }) => {
        const yearSpace = Math.max(
            Math.abs(year - REFERENCE_YEAR),
            MIN_YEAR_SPACE,
        );
        let guesses = yearSpace * 365;
        if (separator) {
            guesses *= 4;
        }
        return guesses;
    };

    const getVariations = (cleanedWord) => {
        const wordArray = cleanedWord.split("");
        const upperCaseCount = wordArray.filter((char) =>
            char.match(ONE_UPPER),
        ).length;
        const lowerCaseCount = wordArray.filter((char) =>
            char.match(ONE_LOWER),
        ).length;
        let variations = 0;
        const variationLength = Math.min(upperCaseCount, lowerCaseCount);
        for (let i = 1; i <= variationLength; i += 1) {
            variations += utils.nCk(upperCaseCount + lowerCaseCount, i);
        }
        return variations;
    };
    var uppercaseVariant = (word) => {
        const cleanedWord = word.replace(ALPHA_INVERTED, "");
        if (
            cleanedWord.match(ALL_LOWER_INVERTED) ||
            cleanedWord.toLowerCase() === cleanedWord
        ) {
            return 1;
        }
        const commonCases = [START_UPPER, END_UPPER, ALL_UPPER_INVERTED];
        const commonCasesLength = commonCases.length;
        for (let i = 0; i < commonCasesLength; i += 1) {
            const regex = commonCases[i];
            if (cleanedWord.match(regex)) {
                return 2;
            }
        }
        return getVariations(cleanedWord);
    };

    const getCounts = ({ subs, subbed, token }) => {
        const unsubbed = subs[subbed];
        const chrs = token.toLowerCase().split("");
        const subbedCount = chrs.filter((char) => char === subbed).length;
        const unsubbedCount = chrs.filter((char) => char === unsubbed).length;
        return {
            subbedCount,
            unsubbedCount,
        };
    };
    var l33tVariant = ({ l33t, sub, token }) => {
        if (!l33t) {
            return 1;
        }
        let variations = 1;
        const subs = sub;
        Object.keys(subs).forEach((subbed) => {
            const { subbedCount, unsubbedCount } = getCounts({
                subs,
                subbed,
                token,
            });
            if (subbedCount === 0 || unsubbedCount === 0) {
                variations *= 2;
            } else {
                const p = Math.min(unsubbedCount, subbedCount);
                let possibilities = 0;
                for (let i = 1; i <= p; i += 1) {
                    possibilities += utils.nCk(unsubbedCount + subbedCount, i);
                }
                variations *= possibilities;
            }
        });
        return variations;
    };

    var dictionaryMatcher$1 = ({ rank, reversed, l33t, sub, token }) => {
        const baseGuesses = rank; // keep these as properties for display purposes
        const uppercaseVariations = uppercaseVariant(token);
        const l33tVariations = l33tVariant({
            l33t,
            sub,
            token,
        });
        const reversedVariations = (reversed && 2) || 1;
        const calculation =
            baseGuesses *
            uppercaseVariations *
            l33tVariations *
            reversedVariations;
        return {
            baseGuesses,
            uppercaseVariations,
            l33tVariations,
            calculation,
        };
    };

    var regexMatcher$1 = ({ regexName, regexMatch, token }) => {
        const charClassBases = {
            alphaLower: 26,
            alphaUpper: 26,
            alpha: 52,
            alphanumeric: 62,
            digits: 10,
            symbols: 33,
        };
        if (regexName in charClassBases) {
            return charClassBases[regexName] ** token.length;
        }
        switch (regexName) {
            case "recentYear":
                return Math.max(
                    Math.abs(parseInt(regexMatch[0], 10) - REFERENCE_YEAR),
                    MIN_YEAR_SPACE,
                );
        }
        return 0;
    };

    var repeatMatcher$1 = ({ baseGuesses, repeatCount }) =>
        baseGuesses * repeatCount;

    var sequenceMatcher$1 = ({ token, ascending }) => {
        const firstChr = token.charAt(0);
        let baseGuesses = 0;
        const startingPoints = ["a", "A", "z", "Z", "0", "1", "9"];
        if (startingPoints.includes(firstChr)) {
            baseGuesses = 4;
        } else if (firstChr.match(/\d/)) {
            baseGuesses = 10; // digits
        } else {
            baseGuesses = 26;
        }
        if (!ascending) {
            baseGuesses *= 2;
        }
        return baseGuesses * token.length;
    };

    const calcAverageDegree = (graph) => {
        let average = 0;
        Object.keys(graph).forEach((key) => {
            const neighbors = graph[key];
            average += neighbors.filter((entry) => !!entry).length;
        });
        average /= Object.entries(graph).length;
        return average;
    };
    const estimatePossiblePatterns = ({ token, graph, turns }) => {
        const startingPosition = Object.keys(
            zxcvbnOptions.graphs[graph],
        ).length;
        const averageDegree = calcAverageDegree(zxcvbnOptions.graphs[graph]);
        let guesses = 0;
        const tokenLength = token.length;
        for (let i = 2; i <= tokenLength; i += 1) {
            const possibleTurns = Math.min(turns, i - 1);
            for (let j = 1; j <= possibleTurns; j += 1) {
                guesses +=
                    utils.nCk(i - 1, j - 1) *
                    startingPosition *
                    averageDegree ** j;
            }
        }
        return guesses;
    };
    var spatialMatcher$1 = ({ graph, token, shiftedCount, turns }) => {
        let guesses = estimatePossiblePatterns({
            token,
            graph,
            turns,
        });
        if (shiftedCount) {
            const unShiftedCount = token.length - shiftedCount;
            if (shiftedCount === 0 || unShiftedCount === 0) {
                guesses *= 2;
            } else {
                let shiftedVariations = 0;
                for (
                    let i = 1;
                    i <= Math.min(shiftedCount, unShiftedCount);
                    i += 1
                ) {
                    shiftedVariations += utils.nCk(
                        shiftedCount + unShiftedCount,
                        i,
                    );
                }
                guesses *= shiftedVariations;
            }
        }
        return Math.round(guesses);
    };

    const getMinGuesses = (match, password) => {
        let minGuesses = 1;
        if (match.token.length < password.length) {
            if (match.token.length === 1) {
                minGuesses = MIN_SUBMATCH_GUESSES_SINGLE_CHAR;
            } else {
                minGuesses = MIN_SUBMATCH_GUESSES_MULTI_CHAR;
            }
        }
        return minGuesses;
    };
    const matchers = {
        bruteforce: bruteforceMatcher$1,
        date: dateMatcher$1,
        dictionary: dictionaryMatcher$1,
        regex: regexMatcher$1,
        repeat: repeatMatcher$1,
        sequence: sequenceMatcher$1,
        spatial: spatialMatcher$1,
    };
    const getScoring = (name, match) => {
        if (matchers[name]) {
            return matchers[name](match);
        }
        if (
            zxcvbnOptions.matchers[name] &&
            "scoring" in zxcvbnOptions.matchers[name]
        ) {
            return zxcvbnOptions.matchers[name].scoring(match);
        }
        return 0;
    };
    var estimateGuesses = (match, password) => {
        const extraData = {};
        if ("guesses" in match && match.guesses != null) {
            return match;
        }
        const minGuesses = getMinGuesses(match, password);
        const estimationResult = getScoring(match.pattern, match);
        let guesses = 0;
        if (typeof estimationResult === "number") {
            guesses = estimationResult;
        } else if (match.pattern === "dictionary") {
            guesses = estimationResult.calculation;
            extraData.baseGuesses = estimationResult.baseGuesses;
            extraData.uppercaseVariations =
                estimationResult.uppercaseVariations;
            extraData.l33tVariations = estimationResult.l33tVariations;
        }
        const matchGuesses = Math.max(guesses, minGuesses);
        return {
            ...match,
            ...extraData,
            guesses: matchGuesses,
            guessesLog10: utils.log10(matchGuesses),
        };
    };

    const scoringHelper = {
        password: "",
        optimal: {},
        excludeAdditive: false,
        fillArray(size, valueType) {
            const result = [];
            for (let i = 0; i < size; i += 1) {
                let value = [];
                if (valueType === "object") {
                    value = {};
                }
                result.push(value);
            }
            return result;
        },
        makeBruteforceMatch(i, j) {
            return {
                pattern: "bruteforce",
                token: this.password.slice(i, +j + 1 || 9e9),
                i,
                j,
            };
        },
        update(match, sequenceLength) {
            const k = match.j;
            const estimatedMatch = estimateGuesses(match, this.password);
            let pi = estimatedMatch.guesses;
            if (sequenceLength > 1) {
                pi *= this.optimal.pi[estimatedMatch.i - 1][sequenceLength - 1];
            }
            let g = utils.factorial(sequenceLength) * pi;
            if (!this.excludeAdditive) {
                g +=
                    MIN_GUESSES_BEFORE_GROWING_SEQUENCE ** (sequenceLength - 1);
            }
            let shouldSkip = false;
            Object.keys(this.optimal.g[k]).forEach((competingPatternLength) => {
                const competingMetricMatch =
                    this.optimal.g[k][competingPatternLength];
                if (parseInt(competingPatternLength, 10) <= sequenceLength) {
                    if (competingMetricMatch <= g) {
                        shouldSkip = true;
                    }
                }
            });
            if (!shouldSkip) {
                this.optimal.g[k][sequenceLength] = g;
                this.optimal.m[k][sequenceLength] = estimatedMatch;
                this.optimal.pi[k][sequenceLength] = pi;
            }
        },
        bruteforceUpdate(passwordCharIndex) {
            let match = this.makeBruteforceMatch(0, passwordCharIndex);
            this.update(match, 1);
            for (let i = 1; i <= passwordCharIndex; i += 1) {
                match = this.makeBruteforceMatch(i, passwordCharIndex);
                const tmp = this.optimal.m[i - 1];
                Object.keys(tmp).forEach((sequenceLength) => {
                    const lastMatch = tmp[sequenceLength];
                    if (lastMatch.pattern !== "bruteforce") {
                        this.update(match, parseInt(sequenceLength, 10) + 1);
                    }
                });
            }
        },
        unwind(passwordLength) {
            const optimalMatchSequence = [];
            let k = passwordLength - 1;
            let sequenceLength = 0;
            let g = 2e308;
            const temp = this.optimal.g[k];
            if (temp) {
                Object.keys(temp).forEach((candidateSequenceLength) => {
                    const candidateMetricMatch = temp[candidateSequenceLength];
                    if (candidateMetricMatch < g) {
                        sequenceLength = parseInt(candidateSequenceLength, 10);
                        g = candidateMetricMatch;
                    }
                });
            }
            while (k >= 0) {
                const match = this.optimal.m[k][sequenceLength];
                optimalMatchSequence.unshift(match);
                k = match.i - 1;
                sequenceLength -= 1;
            }
            return optimalMatchSequence;
        },
    };
    var scoring = {
        mostGuessableMatchSequence(password, matches, excludeAdditive = false) {
            scoringHelper.password = password;
            scoringHelper.excludeAdditive = excludeAdditive;
            const passwordLength = password.length;
            let matchesByCoordinateJ = scoringHelper.fillArray(
                passwordLength,
                "array",
            );
            matches.forEach((match) => {
                matchesByCoordinateJ[match.j].push(match);
            });
            matchesByCoordinateJ = matchesByCoordinateJ.map((match) =>
                match.sort((m1, m2) => m1.i - m2.i),
            );
            scoringHelper.optimal = {
                m: scoringHelper.fillArray(passwordLength, "object"),
                pi: scoringHelper.fillArray(passwordLength, "object"),
                g: scoringHelper.fillArray(passwordLength, "object"),
            };
            for (let k = 0; k < passwordLength; k += 1) {
                matchesByCoordinateJ[k].forEach((match) => {
                    if (match.i > 0) {
                        Object.keys(
                            scoringHelper.optimal.m[match.i - 1],
                        ).forEach((sequenceLength) => {
                            scoringHelper.update(
                                match,
                                parseInt(sequenceLength, 10) + 1,
                            );
                        });
                    } else {
                        scoringHelper.update(match, 1);
                    }
                });
                scoringHelper.bruteforceUpdate(k);
            }
            const optimalMatchSequence = scoringHelper.unwind(passwordLength);
            const optimalSequenceLength = optimalMatchSequence.length;
            const guesses = this.getGuesses(password, optimalSequenceLength);
            return {
                password,
                guesses,
                guessesLog10: utils.log10(guesses),
                sequence: optimalMatchSequence,
            };
        },
        getGuesses(password, optimalSequenceLength) {
            const passwordLength = password.length;
            let guesses = 0;
            if (password.length === 0) {
                guesses = 1;
            } else {
                guesses =
                    scoringHelper.optimal.g[passwordLength - 1][
                        optimalSequenceLength
                    ];
            }
            return guesses;
        },
    };

    /*
     *-------------------------------------------------------------------------------
     * repeats (aaa, abcabcabc) ------------------------------
     *-------------------------------------------------------------------------------
     */
    class MatchRepeat {
        match({ password, omniMatch }) {
            const matches = [];
            let lastIndex = 0;
            while (lastIndex < password.length) {
                const greedyMatch = this.getGreedyMatch(password, lastIndex);
                const lazyMatch = this.getLazyMatch(password, lastIndex);
                if (greedyMatch == null) {
                    break;
                }
                const { match, baseToken } = this.setMatchToken(
                    greedyMatch,
                    lazyMatch,
                );
                if (match) {
                    const j = match.index + match[0].length - 1;
                    const baseGuesses = this.getBaseGuesses(
                        baseToken,
                        omniMatch,
                    );
                    matches.push(
                        this.normalizeMatch(baseToken, j, match, baseGuesses),
                    );
                    lastIndex = j + 1;
                }
            }
            const hasPromises = matches.some((match) => {
                return match instanceof Promise;
            });
            if (hasPromises) {
                return Promise.all(matches);
            }
            return matches;
        }
        normalizeMatch(baseToken, j, match, baseGuesses) {
            const baseMatch = {
                pattern: "repeat",
                i: match.index,
                j,
                token: match[0],
                baseToken,
                baseGuesses: 0,
                repeatCount: match[0].length / baseToken.length,
            };
            if (baseGuesses instanceof Promise) {
                return baseGuesses.then((resolvedBaseGuesses) => {
                    return {
                        ...baseMatch,
                        baseGuesses: resolvedBaseGuesses,
                    };
                });
            }
            return {
                ...baseMatch,
                baseGuesses,
            };
        }
        getGreedyMatch(password, lastIndex) {
            const greedy = /(.+)\1+/g;
            greedy.lastIndex = lastIndex;
            return greedy.exec(password);
        }
        getLazyMatch(password, lastIndex) {
            const lazy = /(.+?)\1+/g;
            lazy.lastIndex = lastIndex;
            return lazy.exec(password);
        }
        setMatchToken(greedyMatch, lazyMatch) {
            const lazyAnchored = /^(.+?)\1+$/;
            let match;
            let baseToken = "";
            if (lazyMatch && greedyMatch[0].length > lazyMatch[0].length) {
                match = greedyMatch;
                const temp = lazyAnchored.exec(match[0]);
                if (temp) {
                    baseToken = temp[1];
                }
            } else {
                match = lazyMatch;
                if (match) {
                    baseToken = match[1];
                }
            }
            return {
                match,
                baseToken,
            };
        }
        getBaseGuesses(baseToken, omniMatch) {
            const matches = omniMatch.match(baseToken);
            if (matches instanceof Promise) {
                return matches.then((resolvedMatches) => {
                    const baseAnalysis = scoring.mostGuessableMatchSequence(
                        baseToken,
                        resolvedMatches,
                    );
                    return baseAnalysis.guesses;
                });
            }
            const baseAnalysis = scoring.mostGuessableMatchSequence(
                baseToken,
                matches,
            );
            return baseAnalysis.guesses;
        }
    }

    /*
     *-------------------------------------------------------------------------------
     * sequences (abcdef) ------------------------------
     *-------------------------------------------------------------------------------
     */
    class MatchSequence {
        constructor() {
            this.MAX_DELTA = 5;
        }
        match({ password }) {
            /*
             * Identifies sequences by looking for repeated differences in unicode codepoint.
             * this allows skipping, such as 9753, and also matches some extended unicode sequences
             * such as Greek and Cyrillic alphabets.
             *
             * for example, consider the input 'abcdb975zy'
             *
             * password: a   b   c   d   b    9   7   5   z   y
             * index:    0   1   2   3   4    5   6   7   8   9
             * delta:      1   1   1  -2  -41  -2  -2  69   1
             *
             * expected result:
             * [(i, j, delta), ...] = [(0, 3, 1), (5, 7, -2), (8, 9, 1)]
             */
            const result = [];
            if (password.length === 1) {
                return [];
            }
            let i = 0;
            let lastDelta = null;
            const passwordLength = password.length;
            for (let k = 1; k < passwordLength; k += 1) {
                const delta =
                    password.charCodeAt(k) - password.charCodeAt(k - 1);
                if (lastDelta == null) {
                    lastDelta = delta;
                }
                if (delta !== lastDelta) {
                    const j = k - 1;
                    this.update({
                        i,
                        j,
                        delta: lastDelta,
                        password,
                        result,
                    });
                    i = j;
                    lastDelta = delta;
                }
            }
            this.update({
                i,
                j: passwordLength - 1,
                delta: lastDelta,
                password,
                result,
            });
            return result;
        }
        update({ i, j, delta, password, result }) {
            if (j - i > 1 || Math.abs(delta) === 1) {
                const absoluteDelta = Math.abs(delta);
                if (absoluteDelta > 0 && absoluteDelta <= this.MAX_DELTA) {
                    const token = password.slice(i, +j + 1 || 9e9);
                    const { sequenceName, sequenceSpace } =
                        this.getSequence(token);
                    return result.push({
                        pattern: "sequence",
                        i,
                        j,
                        token: password.slice(i, +j + 1 || 9e9),
                        sequenceName,
                        sequenceSpace,
                        ascending: delta > 0,
                    });
                }
            }
            return null;
        }
        getSequence(token) {
            let sequenceName = "unicode";
            let sequenceSpace = 26;
            if (ALL_LOWER.test(token)) {
                sequenceName = "lower";
                sequenceSpace = 26;
            } else if (ALL_UPPER.test(token)) {
                sequenceName = "upper";
                sequenceSpace = 26;
            } else if (ALL_DIGIT.test(token)) {
                sequenceName = "digits";
                sequenceSpace = 10;
            }
            return {
                sequenceName,
                sequenceSpace,
            };
        }
    }

    /*
     * ------------------------------------------------------------------------------
     * spatial match (qwerty/dvorak/keypad and so on) -----------------------------------------
     * ------------------------------------------------------------------------------
     */
    class MatchSpatial {
        constructor() {
            this.SHIFTED_RX =
                /[~!@#$%^&*()_+QWERTYUIOP{}|ASDFGHJKL:"ZXCVBNM<>?]/;
        }
        match({ password }) {
            const matches = [];
            Object.keys(zxcvbnOptions.graphs).forEach((graphName) => {
                const graph = zxcvbnOptions.graphs[graphName];
                extend(matches, this.helper(password, graph, graphName));
            });
            return sorted(matches);
        }
        checkIfShifted(graphName, password, index) {
            if (
                !graphName.includes("keypad") &&
                this.SHIFTED_RX.test(password.charAt(index))
            ) {
                return 1;
            }
            return 0;
        }
        helper(password, graph, graphName) {
            let shiftedCount;
            const matches = [];
            let i = 0;
            const passwordLength = password.length;
            while (i < passwordLength - 1) {
                let j = i + 1;
                let lastDirection = 0;
                let turns = 0;
                shiftedCount = this.checkIfShifted(graphName, password, i);
                while (true) {
                    const prevChar = password.charAt(j - 1);
                    const adjacents = graph[prevChar] || [];
                    let found = false;
                    let foundDirection = -1;
                    let curDirection = -1;
                    if (j < passwordLength) {
                        const curChar = password.charAt(j);
                        const adjacentsLength = adjacents.length;
                        for (let k = 0; k < adjacentsLength; k += 1) {
                            const adjacent = adjacents[k];
                            curDirection += 1;
                            if (adjacent) {
                                const adjacentIndex = adjacent.indexOf(curChar);
                                if (adjacentIndex !== -1) {
                                    found = true;
                                    foundDirection = curDirection;
                                    if (adjacentIndex === 1) {
                                        shiftedCount += 1;
                                    }
                                    if (lastDirection !== foundDirection) {
                                        turns += 1;
                                        lastDirection = foundDirection;
                                    }
                                    break;
                                }
                            }
                        }
                    }
                    if (found) {
                        j += 1;
                    } else {
                        if (j - i > 2) {
                            matches.push({
                                pattern: "spatial",
                                i,
                                j: j - 1,
                                token: password.slice(i, j),
                                graph: graphName,
                                turns,
                                shiftedCount,
                            });
                        }
                        i = j;
                        break;
                    }
                }
            }
            return matches;
        }
    }

    class Matching {
        constructor() {
            this.matchers = {
                date: MatchDate,
                dictionary: MatchDictionary,
                regex: MatchRegex,
                repeat: MatchRepeat,
                sequence: MatchSequence,
                spatial: MatchSpatial,
            };
        }
        match(password) {
            const matches = [];
            const promises = [];
            const matchers = [
                ...Object.keys(this.matchers),
                ...Object.keys(zxcvbnOptions.matchers),
            ];
            matchers.forEach((key) => {
                if (!this.matchers[key] && !zxcvbnOptions.matchers[key]) {
                    return;
                }
                const Matcher = this.matchers[key]
                    ? this.matchers[key]
                    : zxcvbnOptions.matchers[key].Matching;
                const usedMatcher = new Matcher();
                const result = usedMatcher.match({
                    password,
                    omniMatch: this,
                });
                if (result instanceof Promise) {
                    result.then((response) => {
                        extend(matches, response);
                    });
                    promises.push(result);
                } else {
                    extend(matches, result);
                }
            });
            if (promises.length > 0) {
                return new Promise((resolve) => {
                    Promise.all(promises).then(() => {
                        resolve(sorted(matches));
                    });
                });
            }
            return sorted(matches);
        }
    }

    const SECOND = 1;
    const MINUTE = SECOND * 60;
    const HOUR = MINUTE * 60;
    const DAY = HOUR * 24;
    const MONTH = DAY * 31;
    const YEAR = MONTH * 12;
    const CENTURY = YEAR * 100;
    const times = {
        second: SECOND,
        minute: MINUTE,
        hour: HOUR,
        day: DAY,
        month: MONTH,
        year: YEAR,
        century: CENTURY,
    };
    /*
     * -------------------------------------------------------------------------------
     *  Estimates time for an attacker ---------------------------------------------------------------
     * -------------------------------------------------------------------------------
     */
    class TimeEstimates {
        translate(displayStr, value) {
            let key = displayStr;
            if (value !== undefined && value !== 1) {
                key += "s";
            }
            const { timeEstimation } = zxcvbnOptions.translations;
            return timeEstimation[key].replace("{base}", `${value}`);
        }
        estimateAttackTimes(guesses) {
            const crackTimesSeconds = {
                onlineThrottling100PerHour: guesses / (100 / 3600),
                onlineNoThrottling10PerSecond: guesses / 10,
                offlineSlowHashing1e4PerSecond: guesses / 1e4,
                offlineFastHashing1e10PerSecond: guesses / 1e10,
            };
            const crackTimesDisplay = {
                onlineThrottling100PerHour: "",
                onlineNoThrottling10PerSecond: "",
                offlineSlowHashing1e4PerSecond: "",
                offlineFastHashing1e10PerSecond: "",
            };
            Object.keys(crackTimesSeconds).forEach((scenario) => {
                const seconds = crackTimesSeconds[scenario];
                crackTimesDisplay[scenario] = this.displayTime(seconds);
            });
            return {
                crackTimesSeconds,
                crackTimesDisplay,
                score: this.guessesToScore(guesses),
            };
        }
        guessesToScore(guesses) {
            const DELTA = 5;
            if (guesses < 1e3 + DELTA) {
                return 0;
            }
            if (guesses < 1e6 + DELTA) {
                return 1;
            }
            if (guesses < 1e8 + DELTA) {
                return 2;
            }
            if (guesses < 1e10 + DELTA) {
                return 3;
            }
            return 4;
        }
        displayTime(seconds) {
            let displayStr = "centuries";
            let base;
            const timeKeys = Object.keys(times);
            const foundIndex = timeKeys.findIndex(
                (time) => seconds < times[time],
            );
            if (foundIndex > -1) {
                displayStr = timeKeys[foundIndex - 1];
                if (foundIndex !== 0) {
                    base = Math.round(seconds / times[displayStr]);
                } else {
                    displayStr = "ltSecond";
                }
            }
            return this.translate(displayStr, base);
        }
    }

    var bruteforceMatcher = () => {
        return null;
    };

    var dateMatcher = () => {
        return {
            warning: zxcvbnOptions.translations.warnings.dates,
            suggestions: [zxcvbnOptions.translations.suggestions.dates],
        };
    };

    const getDictionaryWarningPassword = (match, isSoleMatch) => {
        let warning = "";
        if (isSoleMatch && !match.l33t && !match.reversed) {
            if (match.rank <= 10) {
                warning = zxcvbnOptions.translations.warnings.topTen;
            } else if (match.rank <= 100) {
                warning = zxcvbnOptions.translations.warnings.topHundred;
            } else {
                warning = zxcvbnOptions.translations.warnings.common;
            }
        } else if (match.guessesLog10 <= 4) {
            warning = zxcvbnOptions.translations.warnings.similarToCommon;
        }
        return warning;
    };
    const getDictionaryWarningWikipedia = (match, isSoleMatch) => {
        let warning = "";
        if (isSoleMatch) {
            warning = zxcvbnOptions.translations.warnings.wordByItself;
        }
        return warning;
    };
    const getDictionaryWarningNames = (match, isSoleMatch) => {
        if (isSoleMatch) {
            return zxcvbnOptions.translations.warnings.namesByThemselves;
        }
        return zxcvbnOptions.translations.warnings.commonNames;
    };
    const getDictionaryWarning = (match, isSoleMatch) => {
        let warning = "";
        const dictName = match.dictionaryName;
        const isAName =
            dictName === "lastnames" ||
            dictName.toLowerCase().includes("firstnames");
        if (dictName === "passwords") {
            warning = getDictionaryWarningPassword(match, isSoleMatch);
        } else if (dictName.includes("wikipedia")) {
            warning = getDictionaryWarningWikipedia(match, isSoleMatch);
        } else if (isAName) {
            warning = getDictionaryWarningNames(match, isSoleMatch);
        } else if (dictName === "userInputs") {
            warning = zxcvbnOptions.translations.warnings.userInputs;
        }
        return warning;
    };
    var dictionaryMatcher = (match, isSoleMatch) => {
        const warning = getDictionaryWarning(match, isSoleMatch);
        const suggestions = [];
        const word = match.token;
        if (word.match(START_UPPER)) {
            suggestions.push(
                zxcvbnOptions.translations.suggestions.capitalization,
            );
        } else if (
            word.match(ALL_UPPER_INVERTED) &&
            word.toLowerCase() !== word
        ) {
            suggestions.push(
                zxcvbnOptions.translations.suggestions.allUppercase,
            );
        }
        if (match.reversed && match.token.length >= 4) {
            suggestions.push(
                zxcvbnOptions.translations.suggestions.reverseWords,
            );
        }
        if (match.l33t) {
            suggestions.push(zxcvbnOptions.translations.suggestions.l33t);
        }
        return {
            warning,
            suggestions,
        };
    };

    var regexMatcher = (match) => {
        if (match.regexName === "recentYear") {
            return {
                warning: zxcvbnOptions.translations.warnings.recentYears,
                suggestions: [
                    zxcvbnOptions.translations.suggestions.recentYears,
                    zxcvbnOptions.translations.suggestions.associatedYears,
                ],
            };
        }
        return {
            warning: "",
            suggestions: [],
        };
    };

    var repeatMatcher = (match) => {
        let warning = zxcvbnOptions.translations.warnings.extendedRepeat;
        if (match.baseToken.length === 1) {
            warning = zxcvbnOptions.translations.warnings.simpleRepeat;
        }
        return {
            warning,
            suggestions: [zxcvbnOptions.translations.suggestions.repeated],
        };
    };

    var sequenceMatcher = () => {
        return {
            warning: zxcvbnOptions.translations.warnings.sequences,
            suggestions: [zxcvbnOptions.translations.suggestions.sequences],
        };
    };

    var spatialMatcher = (match) => {
        let warning = zxcvbnOptions.translations.warnings.keyPattern;
        if (match.turns === 1) {
            warning = zxcvbnOptions.translations.warnings.straightRow;
        }
        return {
            warning,
            suggestions: [
                zxcvbnOptions.translations.suggestions.longerKeyboardPattern,
            ],
        };
    };

    const defaultFeedback = {
        warning: "",
        suggestions: [],
    };
    /*
     * -------------------------------------------------------------------------------
     *  Generate feedback ---------------------------------------------------------------
     * -------------------------------------------------------------------------------
     */
    class Feedback {
        constructor() {
            this.matchers = {
                bruteforce: bruteforceMatcher,
                date: dateMatcher,
                dictionary: dictionaryMatcher,
                regex: regexMatcher,
                repeat: repeatMatcher,
                sequence: sequenceMatcher,
                spatial: spatialMatcher,
            };
            this.defaultFeedback = {
                warning: "",
                suggestions: [],
            };
            this.setDefaultSuggestions();
        }
        setDefaultSuggestions() {
            this.defaultFeedback.suggestions.push(
                zxcvbnOptions.translations.suggestions.useWords,
                zxcvbnOptions.translations.suggestions.noNeed,
            );
        }
        getFeedback(score, sequence) {
            if (sequence.length === 0) {
                return this.defaultFeedback;
            }
            if (score > 2) {
                return defaultFeedback;
            }
            const extraFeedback =
                zxcvbnOptions.translations.suggestions.anotherWord;
            const longestMatch = this.getLongestMatch(sequence);
            let feedback = this.getMatchFeedback(
                longestMatch,
                sequence.length === 1,
            );
            if (feedback !== null && feedback !== undefined) {
                feedback.suggestions.unshift(extraFeedback);
                if (feedback.warning == null) {
                    feedback.warning = "";
                }
            } else {
                feedback = {
                    warning: "",
                    suggestions: [extraFeedback],
                };
            }
            return feedback;
        }
        getLongestMatch(sequence) {
            let longestMatch = sequence[0];
            const slicedSequence = sequence.slice(1);
            slicedSequence.forEach((match) => {
                if (match.token.length > longestMatch.token.length) {
                    longestMatch = match;
                }
            });
            return longestMatch;
        }
        getMatchFeedback(match, isSoleMatch) {
            if (this.matchers[match.pattern]) {
                return this.matchers[match.pattern](match, isSoleMatch);
            }
            if (
                zxcvbnOptions.matchers[match.pattern] &&
                "feedback" in zxcvbnOptions.matchers[match.pattern]
            ) {
                return zxcvbnOptions.matchers[match.pattern].feedback(
                    match,
                    isSoleMatch,
                );
            }
            return defaultFeedback;
        }
    }

    /**
     * @link https://davidwalsh.name/javascript-debounce-function
     * @param func needs to implement a function which is debounced
     * @param wait how long do you want to wait till the previous declared function is executed
     * @param isImmediate defines if you want to execute the function on the first execution or the last execution inside the time window. `true` for first and `false` for last.
     */
    var debounce = (func, wait, isImmediate) => {
        let timeout;
        return function debounce(...args) {
            const context = this;
            const later = () => {
                timeout = undefined;
                if (!isImmediate) {
                    func.apply(context, args);
                }
            };
            const shouldCallNow = isImmediate && !timeout;
            if (timeout !== undefined) {
                clearTimeout(timeout);
            }
            timeout = setTimeout(later, wait);
            if (shouldCallNow) {
                return func.apply(context, args);
            }
            return undefined;
        };
    };

    const time = () => new Date().getTime();
    const createReturnValue = (resolvedMatches, password, start) => {
        const feedback = new Feedback();
        const timeEstimates = new TimeEstimates();
        const matchSequence = scoring.mostGuessableMatchSequence(
            password,
            resolvedMatches,
        );
        const calcTime = time() - start;
        const attackTimes = timeEstimates.estimateAttackTimes(
            matchSequence.guesses,
        );
        return {
            calcTime,
            ...matchSequence,
            ...attackTimes,
            feedback: feedback.getFeedback(
                attackTimes.score,
                matchSequence.sequence,
            ),
        };
    };
    const main = (password, userInputs) => {
        if (userInputs) {
            zxcvbnOptions.extendUserInputsDictionary(userInputs);
        }
        const matching = new Matching();
        return matching.match(password);
    };
    const zxcvbn = (password, userInputs) => {
        const start = time();
        const matches = main(password, userInputs);
        if (matches instanceof Promise) {
            throw new Error(
                "You are using a Promised matcher, please use `zxcvbnAsync` for it.",
            );
        }
        return createReturnValue(matches, password, start);
    };
    const zxcvbnAsync = async (password, userInputs) => {
        const usedPassword = password.substring(0, zxcvbnOptions.maxLength);
        const start = time();
        const matches = await main(usedPassword, userInputs);
        return createReturnValue(matches, usedPassword, start);
    };

    exports.Options = Options;
    exports.debounce = debounce;
    exports.zxcvbn = zxcvbn;
    exports.zxcvbnAsync = zxcvbnAsync;
    exports.zxcvbnOptions = zxcvbnOptions;

    return exports;
})({});
